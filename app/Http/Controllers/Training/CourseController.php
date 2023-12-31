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
use App\Models\Training\CourseRegistration;
use App\Models\Training\Group;
use App\Models\Training\Role;
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

        $user = auth()->user()->roles()->first();

        // If auth user Admin Or Super Admin => all courses
        if(is_super_admin() || $user->role_type_id == 510){
            $courses = Course::with(['upload', 'user','deliveryMethod']);
        }else{  // (Not All courses  just registared)
            $role_instructor_id = Role::select('id')->where('role_type_id',511)
                                    ->where('branch_id',getCurrentUserBranchData()->branch_id)
                                    ->first()->id;
            $courses = Course::with(['upload'])
                        ->whereHas('users', function($q) use($role_instructor_id){
                return $q->where('user_id',auth()->user()->id)->where('courses_registration.role_id',$role_instructor_id);
            });
        }

        $courses->where('branch_id',getCurrentUserBranchData()->branch_id);

        $assigned_learners_sql = CourseRegistration::getCoursesNo(null,512);
        // dd($assigned_learners_sql->count());
        $assigned_instructors_sql = CourseRegistration::getCoursesNo(null,511);
        if (request()->has('course_search') && !is_null(request()->course_search)) {
            $courses = $this->SearchCond($courses);
            $assigned_instructors_sql = $this->SearchCond($assigned_learners_sql);
            $assigned_instructors_sql = $this->SearchCond($assigned_instructors_sql);
        }

        if (request()->has('category_id') && request()->category_id != -1){

            $courses = $courses->where('courses.category_id', request()->category_id);
            $assigned_learners_sql = $assigned_learners_sql->where('courses.category_id', request()->category_id);
            $assigned_instructors_sql = $assigned_instructors_sql->where('courses.category_id', request()->category_id);
        }

        if (request()->has('training_option_id') && request()->training_option_id != -1){
            $courses = $courses->where('courses.training_option_id', request()->training_option_id);
            $assigned_learners_sql = $assigned_learners_sql->where('courses.training_option_id', request()->training_option_id);
            $assigned_instructors_sql = $assigned_instructors_sql->where('courses.training_option_id', request()->training_option_id);
        }


        $count = $courses->count();
        $courses = $courses->page();

        $assigned_learners =  $assigned_learners_sql->count();
        // dump($assigned_learners_sql->count());
        $assigned_instructors =  $assigned_instructors_sql->count();

        $completed_learners =  $assigned_learners_sql->whereRaw('courses_registration.progress >= courses.complete_progress')
                                                    ->where('courses_registration.progress','!=',0)
                                                    ->count();
        // dd($assigned_learners_sql->count());
        $categories = Category::get();
        $delivery_methods = Constant::where('parent_id', 10)->get();

        return Active::Index(compact('courses', 'count', 'post_type', 'trash','assigned_learners','assigned_instructors','completed_learners','categories','delivery_methods'));
    }


    private function SearchCond($eloquent){
        $eloquent1 = $eloquent->where(function ($query) {
            $query->where('courses.title', 'like', '%' . request()->course_search . '%');
        });
        return $eloquent1;
    }


//////////////////////// Create Course /////////////////////////////////////////////
    public function create(){
        $certificate_types = Constant::where('parent_id', 323)->get();
        $certificate_ids = Certificate::whereNull('parent_id')->where('branch_id',getCurrentUserBranchData()->branch_id)->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $categories = Category::where('branch_id',getCurrentUserBranchData()->branch_id)->get();
        return Active::Create(compact( 'certificate_types','delivery_methods','certificate_ids','categories'));
    }

    public function store(CourseRequest $request){
        $validated = $this->Validated($request->validated());
        $validated['created_by'] = auth()->user()->id;
        $validated['branch_id']  = getCurrentUserBranchData()->branch_id;
        $course = Course::create($validated);
        $this->uploadsVideo($course, 'intro_video', null);

        return Active::Inserted($course->trans_title);
    }

//////////////////////// Edit Course /////////////////////////////////////////////

    public function edit(Course $course){
        if (getCurrentUserBranchData()->branch_id != $course->branch_id){
            abort(404);
        }
        $certificate_types = Constant::where('parent_id', 323)->get();
        $certificate_ids = Certificate::whereNull('parent_id')->where('branch_id',getCurrentUserBranchData()->branch_id)->get();
        $delivery_methods = Constant::where('parent_id', 10)->get();
        $categories = Category::where('branch_id',getCurrentUserBranchData()->branch_id)->get();

        return Active::Edit(['eloquent'=>$course, 'delivery_methods' => $delivery_methods,  'certificate_types'=>$certificate_types,'certificate_ids'=>$certificate_ids,'categories'=>$categories]);
    }


    public function update(CourseRequest $request, Course $course){
        if (getCurrentUserBranchData()->branch_id != $course->branch_id){
            abort(404);
        }
        $validated = $this->Validated($request->validated());

        $course->update($validated);
        Course::UploadFile($course, ['method'=>'update']);

        $this->uploadsVideo($course, 'intro_video', null);

        return Active::Updated($course->trans_title);
    }



//////////////////////// Destroy Course /////////////////////////////////////////////

    public function destroy(Course $course){
        Course::where('id', $course->id)->where('branch_id',getCurrentUserBranchData()->branch_id)->SoftTrash();
        return Active::Deleted($course->trans_title);
    }


//////////////////////// Restore Course /////////////////////////////////////////////

    public function restore($course){
        Course::where('id', $course)->where('branch_id',getCurrentUserBranchData()->branch_id)->RestoreFromTrash();
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


    public function duplicate_course()
    {
        $course_id = request()->course_id;

        $c = Course::where('id',$course_id)->first();
        if (!$c){
            abort(404);
        }

        $json = $c->title;
        $json = json_decode($json, true);
        $en = $json['en'];$ar = $json['ar'];


        $insertQuery = "insert into courses (title	,excerpt	,ref_id	,training_option_id	,complete_progress	,accredited_notes,disclaimer	,post_type 	,PDUs	,price	,	exam_price	,	take2_price	,	take2_price_usd	,	rating	,reviews,branch_id	,created_by, 	trashed_status	,slug	,`order`	,group_id	,algolia_order	,xero_code	,xero_exam_code	,material_cost	,short_title	,created_at		,	exam_is_included	,partner_id	,certificate_type_id	,certificate_id	,type_id	,show_in_website,active,wp_id	,wp_city	,wp_migrate	,post_year	,reference_course_id	,`code`	,certificate_no	,category_id) SELECT title	,excerpt	,ref_id	,training_option_id	,complete_progress	,accredited_notes	,disclaimer	,post_type 	,PDUs	,price	,	exam_price	,	take2_price	,	take2_price_usd	,	rating	,reviews	,branch_id	,'".auth()->user()->id."',trashed_status	,slug	,`order`	,group_id	,algolia_order	,xero_code	,xero_exam_code	,material_cost	,short_title	,'".now()."',	exam_is_included	,partner_id	,certificate_type_id	,certificate_id	,type_id	,show_in_website,active,wp_id	,wp_city	,wp_migrate	,post_year	,reference_course_id	,`code`	,certificate_no	,category_id
                            FROM courses where id = $course_id " ;

        DB::insert($insertQuery);
        $inserted_id = DB::getPdo()->lastInsertId();

        DB::table('courses')
            ->where('id', $inserted_id)
            ->update(['title->en' => $en.'_copy_'.$inserted_id,
                    'title->ar' => $ar.'_copy_'.$inserted_id]);

        return redirect()->route('training.courses.index',['course_search'=>$en]);
    }

}
