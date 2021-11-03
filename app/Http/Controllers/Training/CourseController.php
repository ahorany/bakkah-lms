<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Course;
use App\Constant;
use App\Models\Training\Group;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'courses';
    }

    public function index(){

        $post_type = 'course';
        $trash = GetTrash();
        $courses = Course::with(['upload', 'user']);
        $categories = Constant::where('post_type', 'course')->get();

        if(!is_null(request()->course_search)) {
            $courses = $courses->where(function($query){
                $query->where('title', 'like', '%'.request()->course_search.'%')
                ->orWhere('slug', 'like', '%'.request()->course_search.'%');
            });
        }
        if(request()->has('category_id') && request()->category_id!=-1){
            $courses = $courses->whereHas('postMorphs', function (Builder $query){
                $query->where('constant_id', request()->category_id);
            });
        }

        // if(request()->has('show_in_website')){
            $show_in_website = request()->has('show_in_website')?1:0;
            $courses = $courses->where('show_in_website', $show_in_website);
        // }

        $count = $courses->count();
        $courses = $courses->page();
        return Active::Index(compact('courses', 'count', 'post_type', 'trash', 'categories'));
    }

    public function create(){
        $groups = Group::all();
        $partners = Partner::GetPartners('partners', -1, false, 1, 0);
        $certificate_types = Constant::where('parent_id', 323)->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();

        // $params = $this->_create_edit_params();
        // , ['certificate_types'=>$params['certificate_types']]
        return Active::Create(compact('partners', 'certificate_types','groups','delivery_methods'));
    }

    public function store(CourseRequest $request){

        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;

        $course = Course::create($validated);

        Course::SetMorph($course->id);
        Course::AddMorph($course->id, [22]);//25

        $this->uploadsPDF($course, 'pdf', 'en');
        $this->uploadsPDF($course, 'pdf', 'ar');

        $this->uploadsVideo($course, 'intro_video', null);

        \App\Models\SEO\Seo::seo($course);

        return Active::Inserted($course->trans_title);
    }

    public function edit(Course $course){
        $partners = Partner::GetPartners('partners', -1, false, 1, 0);
        $certificate_types = Constant::where('parent_id', 323)->get();
        $groups = Group::all();
        $delivery_methods = Constant::where('parent_id', 10)->get();

        return Active::Edit(['eloquent'=>$course, 'delivery_methods' => $delivery_methods, 'groups' => $groups, 'partners'=>$partners, 'certificate_types'=>$certificate_types]);
    }

    public function update(CourseRequest $request, Course $course){
        $validated = $this->Validated($request->validated());
        Course::find($course->id)->update($validated);
        Course::SetMorph($course->id);
        Course::UploadFile($course, ['method'=>'update']);


        $this->uploadsPDF($course, 'pdf', 'en');
        $this->uploadsPDF($course, 'pdf', 'ar');

        $this->uploadsVideo($course, 'intro_video', null);

        \App\Models\SEO\Seo::seo($course);
        return Active::Updated($course->trans_title);
    }


    public function destroy(Course $course){
        Course::where('id', $course->id)->SoftTrash();
        return Active::Deleted($course->trans_title);
    }

    public function restore($course){
        Course::where('id', $course)->RestoreFromTrash();
        $course = Course::where('id', $course)->first();
        return Active::Restored($course->trans_title);
    }

    private function Validated($validated){
        $validated['title'] = null;
        $validated['excerpt'] = null;
        $validated['pdf'] = null;
        $validated['short_title'] = null;
        $validated['accredited_notes'] = null;
        $validated['disclaimer'] = null;
        $validated['updated_by'] = auth()->user()->id;

        $validated['show_in_website'] = request()->has('show_in_website')?1:0;
        $validated['active'] = request()->has('active')?1:0;

        return $validated;
    }

    private function uploadsPDF($model, $name='pdf', $locale='en'){

        $full_name = $locale.'_'.$name;
        if(request()->has($full_name)){

            $upload = $model->uploads()
            ->where('post_type', $name)
            ->where('locale', $locale)
            ->first();

            $pdf = request()->file($full_name);
            $title = $pdf->getClientOriginalName();

            $fileName = date('Y-m-d-H-i-s') . '-' . $full_name. '-' . $title;
            $fileName = strtolower($fileName);

            if($pdf->move(public_path('upload/pdf/'), $fileName)){

                if(is_null($upload))
                {
                    $model->uploads()->create([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
                else
                {
                    $this->unlinkPDF($name, $upload->file);
                    $model->uploads()->where('post_type', $name)
                    ->where('locale', $locale)
                    ->update([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'pdf',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
            }
        }
    }

    private function unlinkPDF($name, $image){
        if(request()->hasFile($name) && !empty($name) && !is_null($name) && !empty($image) && !is_null($image))
        {
            if(file_exists(public_path('/upload/pdf/') . $image)){
                unlink(public_path('/upload/pdf/') . $image);
            }
        }
    }


    private function uploadsVideo($model, $name='video', $locale='en'){

        $full_name = $name;
        if($locale) {
            $full_name = $locale.'_'.$name;
        }

        if(request()->has($full_name)){
            // dd($full_name);
            $upload = $model->uploads()
            ->where('post_type', $name)
            ->where('locale', $locale)
            ->first();

            $pdf = request()->file($full_name);
            $title = $pdf->getClientOriginalName();

            $fileName = date('Y-m-d-H-i-s') . '-' . $full_name. '-' . $title;
            $fileName = strtolower($fileName);

            if($pdf->move(public_path('upload/video/'), $fileName)){

                if(is_null($upload))
                {
                    $model->uploads()->create([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'mp4',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
                else
                {
                    $this->unlinkVideo($name, $upload->file);
                    $model->uploads()->where('post_type', $name)
                    ->where('locale', $locale)
                    ->update([
                        'title'=>$title,
                        'file'=>$fileName,
                        'extension'=>'mp4',
                        'post_type'=>$name,
                        'created_by'=>$model->created_by,
                        'updated_by'=>$model->updated_by,
                        'locale'=>$locale,
                    ]);
                }
            }
        }
    }

    private function unlinkVideo($name, $image){
        if(request()->hasFile($name) && !empty($name) && !is_null($name) && !empty($image) && !is_null($image))
        {
            if(file_exists(public_path('/upload/video/') . $image)){
                unlink(public_path('/upload/video/') . $image);
            }
        }
    }
}
