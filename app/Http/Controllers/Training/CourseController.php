<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Course;
use App\Constant;
use App\Models\Training\Certificate;
use App\Models\Training\Group;
use App\Models\Training\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use DB;

// use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.courses.index', ['only' => ['index']]);
        $this->middleware('permission:course.create', ['only' => ['create','store']]);
        $this->middleware('permission:course.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:course.delete', ['only' => ['destroy']]);
        $this->middleware('permission:course.restore', ['only' => ['restore']]);


        Active::$namespace = 'training';
        Active::$folder = 'courses';
    }

    public function index(){

        $post_type = 'course';
        $trash = GetTrash();
        if($trash){
            if(checkUserIsTrainee()){
                abort(404);
            }
        }

        //dd(auth()->user()->id);
        if(checkUserIsTrainee()){
            $courses = Course::with(['upload'])->whereHas('users', function($q){
                return $q->where('user_id',auth()->user()->id)->where('courses_registration.role_id',2);
            });
        }else{
            $courses = Course::with(['upload', 'user','deliveryMethod']);
        }

        if (!is_null(request()->course_search)) {
            $courses = $this->SearchCond($courses);
        }


        // $categories = Constant::where('post_type', 'course')->get();

        $count = $courses->count();
        $courses = $courses->page();

        /*
          $courses = DB::table('courses')->where('progress',0);
        if (!is_null(request()->course_search)) {
            $courses = $courses->join('users','users.id','courses_registration.user_id');
            $courses = $this->SearchCond($courses);
        }
        $courses = $courses->count();
        */
        $assigned_learners = DB::table('courses_registration')->where('role_id',3);
        if(!is_null(request()->course_search)) {
            $assigned_learners = $assigned_learners->join('courses','courses.id','courses_registration.course_id');

            $assigned_learners = $this->SearchCond($assigned_learners);
        }
        $assigned_learners = $assigned_learners->count();

        $assigned_instructors = DB::table('courses_registration')->where('role_id',2);
        if(!is_null(request()->course_search)) {
            $assigned_instructors = $assigned_instructors->join('courses','courses.id','courses_registration.course_id');

            $assigned_instructors = $this->SearchCond($assigned_instructors);
        }
        $assigned_instructors = $assigned_instructors->count();


        $completed_learners = DB::table('courses_registration')->where('role_id',3)->where('progress',100);
        if(!is_null(request()->course_search)) {
            $completed_learners = $completed_learners->join('courses','courses.id','courses_registration.course_id');

            $completed_learners = $this->SearchCond($completed_learners);
        }
        $completed_learners = $completed_learners->count();
        // dd($completed_learners);
        return Active::Index(compact('courses', 'count', 'post_type', 'trash','assigned_learners','assigned_instructors','completed_learners'));
    }


    private function SearchCond($eloquent){

        $eloquent1 = $eloquent->where(function ($query) {
            $query->where('courses.title', 'like', '%' . request()->course_search . '%');
        });
        return $eloquent1;
    }

    public function create(){

//        if(checkUserIsTrainee()){
//            abort(404);
//        }
//        $groups = Group::all();
        $partners = Partner::GetPartners('partners', -1, false, 1, 0);
        $certificate_types = Constant::where('parent_id', 323)->get();
        $certificate_ids = Certificate::whereNull('parent_id')->get();

        $delivery_methods = Constant::where('parent_id', 10)->get();

        // $params = $this->_create_edit_params();
        // , ['certificate_types'=>$params['certificate_types']]
        return Active::Create(compact('partners', 'certificate_types','delivery_methods','certificate_ids'));
    }

    public function store(CourseRequest $request){

//        if(checkUserIsTrainee()){
//            abort(404);
//        }
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
//        if(checkUserIsTrainee()){
//            abort(404);
//        }
        $partners = Partner::GetPartners('partners', -1, false, 1, 0);
        $certificate_types = Constant::where('parent_id', 323)->get();
        $certificate_ids = Certificate::whereNull('parent_id')->get();
//        $groups = Group::all();
        $delivery_methods = Constant::where('parent_id', 10)->get();

        return Active::Edit(['eloquent'=>$course, 'delivery_methods' => $delivery_methods, 'partners'=>$partners, 'certificate_types'=>$certificate_types,'certificate_ids'=>$certificate_ids]);
    }

    public function update(CourseRequest $request, Course $course){
//        if(checkUserIsTrainee()){
//            abort(404);
//        }
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
//        if(checkUserIsTrainee()){
//            abort(404);
//        }
        Course::where('id', $course->id)->SoftTrash();
        return Active::Deleted($course->trans_title);
    }

    public function restore($course){
//        if(checkUserIsTrainee()){
//            abort(404);
//        }
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


    public function cloneCourse($course_id){
        $course =  Course::with(['units.subunits','contents' => function($q){
            $q->with(['details','upload','exams','questions.answers']);
        }])->first();
        $this->cloneUnits($course->units,'');
//         dd($course->units);
dd('');
        // store course
      $course_clone =  Course::create([
              "title" => $course->title,
              "excerpt" => $course->excerpt,
              "training_option_id" => $course->training_option_id,
              "accredited_notes" => $course->accredited_notes,
              "disclaimer" => $course->disclaimer,
              "PDUs" => $course->PDUs,
              "post_type" => $course->post_type,
              "price" => $course->price,
              "exam_price" => $course->exam_price,
              "take2_price" => $course->take2_price,
              "take2_price_usd" => $course->take2_price_usd,
              "rating" => $course->rating,
              "reviews" => $course->reviews,
              "created_by" => auth()->id(),
              "updated_by" => auth()->id(),
              "trashed_status" => $course->trashed_status,
              "slug" => $course->slug,
              "order" => $course->order,
              "algolia_order" => $course->algolia_order,
              "xero_code" => $course->xero_code,
              "xero_exam_code" => $course->xero_exam_code,
              "material_cost" => $course->material_cost,
              "created_at" => Carbon::now(),
              "updated_at" => Carbon::now(),
              "short_title" => $course->short_title,
              "deleted_at" => $course->deleted_at,
              "exam_is_included" => $course->exam_is_included,
              "partner_id" => $course->partner_id,
              "certificate_type_id" => $course->certificate_type_id,
              "type_id" => $course->type_id,
              "show_in_website" => $course->show_in_website,
              "active" => $course->active,
              "wp_id" => $course->wp_id,
              "wp_city" => $course->wp_city,
              "wp_migrate" => $course->wp_migrate,
              "post_year" => $course->post_year,
              "reference_course_id" => $course->reference_course_id,
              "code" => $course->code,
        ]);


        return $course;
    }

    private function cloneUnits($course_units,$clone_course_id){
        $course_units = $this->buildTree($course_units);
        dd($course_units);
         foreach ($course_units as $unit){
             Unit::create([
                ""
             ]);
         }
    }

    private function cloneContent($course_contents,$clone_course_id){

    }

    private function buildTree($elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
//            dump($element->parent_id);
//            dump($parentId);
//            dump($element->parent_id == $parentId);

            if ($element->parent_id == $parentId) {
                $children = $this->buildTree($elements, $element->id);
                if ($children) {
                    $element->s = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }



}
