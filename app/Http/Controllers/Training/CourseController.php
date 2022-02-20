<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Course;
use App\Models\Training\Category;

use App\Constant;
use App\Models\Training\Certificate;
use App\Models\Training\Group;
use App\Models\Training\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use DB;


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

        // If auth user Instructor (Not All courses  just registared)
        if(auth()->user()->hasRole(['Instructor'])){
            $courses = Course::with(['upload'])->whereHas('users', function($q){
                return $q->where('user_id',auth()->user()->id)->where('courses_registration.role_id',2);
            });

        }else{ // if admin all courses
            $courses = Course::with(['upload', 'user','deliveryMethod']);
        }

        if (request()->has('course_search') && !is_null(request()->course_search)) {
            $courses = $this->SearchCond($courses);
        }

        if (request()->has('category_id') && request()->category_id != -1){
            $courses = $courses->where('courses.category_id', request()->category_id);
        }


        $count = $courses->count();
        $courses = $courses->page();


        $assigned_learners = $this->getAssignedLearners();
        $assigned_instructors = $this->getAssignedInstructors();
        $completed_learners = $this->getCompletedLearners();

        $categories = Category::get();

        return Active::Index(compact('courses', 'count', 'post_type', 'trash','assigned_learners','assigned_instructors','completed_learners','categories'));
    }


    private function SearchCond($eloquent){
        $eloquent1 = $eloquent->where(function ($query) {
            $query->where('courses.title', 'like', '%' . request()->course_search . '%');
        });
        return $eloquent1;
    }

    private function getAssignedLearners(){
        $assigned_learners = DB::table('courses_registration')->where('role_id',3);
        if(!is_null(request()->course_search)) {
            $assigned_learners = $assigned_learners->join('courses','courses.id','courses_registration.course_id');

            $assigned_learners = $this->SearchCond($assigned_learners);
        }
        return $assigned_learners->count();
    }

    private function getAssignedInstructors(){
        $assigned_instructors = DB::table('courses_registration')->where('role_id',2);
        if(!is_null(request()->course_search)) {
            $assigned_instructors = $assigned_instructors->join('courses','courses.id','courses_registration.course_id');

            $assigned_instructors = $this->SearchCond($assigned_instructors);
        }
        return  $assigned_instructors->count();
    }

    private function getCompletedLearners(){
        $completed_learners = DB::table('courses_registration')->where('role_id',3)->where('progress',100);
        if(!is_null(request()->course_search)) {
            $completed_learners = $completed_learners->join('courses','courses.id','courses_registration.course_id');

            $completed_learners = $this->SearchCond($completed_learners);
        }
        return  $completed_learners->count();
    }






//////////////////////// Create Course /////////////////////////////////////////////
    public function create(){
        $certificate_types = Constant::where('parent_id', 323)->get();
        $certificate_ids = Certificate::whereNull('parent_id')->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $categories = Category::get();
        return Active::Create(compact( 'certificate_types','delivery_methods','certificate_ids','categories'));
    }

    public function store(CourseRequest $request){
        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;

        $course = Course::create($validated);
        $this->uploadsVideo($course, 'intro_video', null);

        return Active::Inserted($course->trans_title);
    }





//////////////////////// Edit Course /////////////////////////////////////////////

    public function edit(Course $course){
        $certificate_types = Constant::where('parent_id', 323)->get();
        $certificate_ids = Certificate::whereNull('parent_id')->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $categories = Category::get();

        return Active::Edit(['eloquent'=>$course, 'delivery_methods' => $delivery_methods,  'certificate_types'=>$certificate_types,'certificate_ids'=>$certificate_ids,'categories'=>$categories]);
    }


    public function update(CourseRequest $request, Course $course){
        $validated = $this->Validated($request->validated());

        $course->update($validated);
        Course::UploadFile($course, ['method'=>'update']);

        $this->uploadsVideo($course, 'intro_video', null);

        return Active::Updated($course->trans_title);
    }



//////////////////////// Destroy Course /////////////////////////////////////////////

    public function destroy(Course $course){
        Course::where('id', $course->id)->SoftTrash();
        return Active::Deleted($course->trans_title);
    }


//////////////////////// Restore Course /////////////////////////////////////////////

    public function restore($course){
        Course::where('id', $course)->RestoreFromTrash();
        $course = Course::where('id', $course)->first();
        return Active::Restored($course->trans_title);
    }



//////////////////////// Other private functions /////////////////////////////////////////////

    private function Validated($validated){
        $validated['title'] = null;
        $validated['excerpt'] = null;
        $validated['short_title'] = null;
        $validated['accredited_notes'] = null;
        $validated['disclaimer'] = null;
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;

        return $validated;
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
