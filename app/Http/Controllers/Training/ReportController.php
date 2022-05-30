<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\User;
use App\Models\Training\Course;
use App\Models\Training\Session;
use App\Models\Training\Content;

use App\Constant;
use App\Models\Training\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\Training\CourseRegistration;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Models\Training\UserExam;
use App\Helpers\CourseContentHelper;
use App\Models\Paginator;
use App\Models\Training\UserContentsPdf;
use App\Models\Training\Message;
use App\Models\Training\Discussion;

use App\Exports\UsersExport;
use App\Exports\CoursesTestsExport;
use App\Exports\AssessmentExport;
use App\Exports\CoursesExport;
use App\Exports\CoursesScormsExport;
use App\Exports\usersTestsExport;
use App\Exports\usersScormExport;
use App\Exports\scormUsersExport;
use App\Exports\testUsersExport;
use App\Exports\userOverviewExport;
use App\Exports\CourseOverviewExport;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.scormsReportOverview', ['only' => ['scormsReportOverview', 'scormsReportScorms']]);
        $this->middleware('permission:user.report', ['only' => ['usersReportOverview']]);

        Active::$namespace  = 'training';
        Active::$folder     = 'reports';
    }

    /////////////////   Refactor code  /////////////////////////////


    public function usersReportOverview()
    {

        $user_id = request()->id;
        $course_id = request()->course_id??null;

        $show_all = request()->show_all??1;

        $course = '';
        if(!is_null($course_id))
        {
            $sql2 = "SELECT id,title
                    FROM courses
                    where id = ? " ;
            $course = DB::select($sql2,[$course_id]);
        }

        $user = User::getUser($user_id);

        // $user = User::findOrFail($user_id);

        // dd($user);
        $users_no            = 1;
        $complete_courses_no =  CourseRegistration::getCoursesNo($course_id)
                                    ->where('courses_registration.user_id',$user_id)
                                    ->whereRaw('courses_registration.progress >= courses.complete_progress')
                                    ->count();
        $courses_in_progress =  CourseRegistration::getCoursesNo($course_id)
                                    ->whereRaw('courses_registration.progress < courses.complete_progress')
                                    ->where('courses_registration.progress','>',0)
                                    ->where('courses_registration.user_id',$user_id)->count();
        $courses_not_started = CourseRegistration::getCoursesNo($course_id)
                                    ->where('courses_registration.progress',0)
                                    ->where('courses_registration.user_id',$user_id)->count();
        $overview = 1;

        if(isset(request()->export))
        {
            $from_course = $this->usersReportCourseFromSql(null,1);
            $from_test = $this->usersReportTestFromSql(null,1);
            $from_scorm = $this->usersReportScormFromSql(null,1);
            return Excel::download(new userOverviewExport($user_id,$complete_courses_no,$from_course, $from_test,$from_scorm), 'userOverview.xlsx');
        }

        return view('training.reports.users.user_report',compact('user_id','users_no','complete_courses_no',
            'courses_in_progress','courses_not_started','overview', 'user','course','show_all'));
    }

    public function usersReportCourse()
    {
        $user_id = request()->id;
        $course_id = request()->course_id??null;

        $course = '';
        if(!is_null($course_id))
        {
            $sql2 = "SELECT id,title FROM courses where id = ? " ;
            $course = DB::select($sql2,[$course_id]);
        }
        $show_all = request()->show_all??1;

        $user = User::getUser($user_id);
        // dd($user_id);
        $branch_id = getCurrentUserBranchData()->branch_id;
        // $courses = CourseRegistration::getCoursesNo()->where('courses_registration.user_id',$user_id)->get();
        // dd($courses);complete_progress

        $select = 'select courses.id,courses.title,courses_registration.progress,courses_registration.score,courses.created_at,courses.PDUs,courses.complete_progress,courses_registration.id as c_reg_id,categories.title as categ_title,courses.training_option_id as deleviry_method_id  ,constants.name  as deleviry_method_name ';

        $from = $this->usersReportCourseFromSql($course_id,$show_all);

        // dd($from);
        $sql2 = $select.$from;
        if(!is_null($course_id) && $show_all == 0)
        {
            $courses = DB::select($sql2, [$branch_id,512, $branch_id, $course_id,$branch_id, $user_id]);
        }
        else
        {
            $courses = DB::select($sql2, [$branch_id,512, $branch_id, $branch_id, $user_id]);
        }

        $paginator = Paginator::GetPaginator($courses);
        $courses = $paginator->items();
        $count = $paginator->total();
        if(isset(request()->export))
        {
            // dd($course_id);
            return Excel::download(new CoursesExport($from, $user_id,$course_id,$show_all), 'Courses.xlsx');
        }

        return view('training.reports.users.user_report',compact('courses', 'user','paginator','count','course','show_all'));
    }



    public function usersReportTest()
    {
        $user_id = request()->id;
        $course_id = request()->course_id??null;
        $course = '';
        if(!is_null($course_id))
        {
            $sql2 = "SELECT id,title FROM courses where id = ? " ;
            $course = DB::select($sql2,[$course_id]);
        }
        $show_all = request()->show_all??1;

        $user = User::getUser($user_id);

        $branch_id = getCurrentUserBranchData()->branch_id;
        $select = "select exams.id , contents.title as content_title, courses.title as course_title, user_exams.time
        , exams.exam_mark, exams.pass_mark, user_exams.mark as exam_trainee_mark, user_exams.status,
        courses.id as course_id,contents.id as content_id ";

        $from = $this->usersReportTestFromSql($course_id,$show_all);

        $sql2 = $select.$from;
        // dd($sql2);
        if(!is_null($course_id) && $show_all == 0)
            $tests = DB::select($sql2, [$user_id, $branch_id, $course_id,$user_id, 512, $branch_id]);
        else
            $tests = DB::select($sql2, [$user_id, $branch_id, $user_id, 512, $branch_id]);

        $paginator = Paginator::GetPaginator($tests);
        $tests = $paginator->items();
        $count = $paginator->total();

        if(isset(request()->export))
        {
            return Excel::download(new usersTestsExport($from,$course_id,$user_id,$show_all), 'usersTests_.xlsx');
        }

        return view('training.reports.users.user_report',compact('user_id', 'tests', 'user','paginator','count','course','show_all'));
    }

    public function usersReportScorm()
    {

        $branch_id  = getCurrentUserBranchData()->branch_id;
        $user_id    = request()->id;
        $course_id  = request()->course_id??null;
        $course = '';
        if(!is_null($course_id))
        {
            $sql2 = "SELECT id,title FROM courses where id = ? " ;
            $course = DB::select($sql2,[$course_id]);
        }

        $show_all = request()->show_all??1;

        $user = User::getUser($user_id);
        // dd($user);
        $select = " select contents.id,courses.title as crtitle,contents.title as cotitle,scormvars_master.date,
        scormvars_master.score,scormvars_master.lesson_status,courses.id as course_id ";

        $from = $this->usersReportScormFromSql($course_id,$show_all);

        $sql2 = $select.$from;
        // dd($user);
        if(!is_null($course_id) && $show_all == 0)
            $scorms = DB::select($sql2, [$user_id,$branch_id,$course_id]);
        else
            $scorms = DB::select($sql2, [$user_id,$branch_id]);

        $paginator = Paginator::GetPaginator($scorms);
        $scorms = $paginator->items();
        $count  = $paginator->total();

        if(isset(request()->export))
        {
            return Excel::download(new usersScormExport($from,$course_id,$user_id,$show_all), 'usersScorms_.xlsx');
        }

        return view('training.reports.users.user_report',compact('user_id', 'scorms','user','paginator','count','course','show_all'));


    }


    //////////////////////////////////////////////////////////


    public function scormsReportOverview()
    {
        // $user_id = request()->id;
        // $user = User::find($user_id);
        $count = DB::table('scormvars_master')->distinct('content_id')->count();
        // $contents_unique = array_unique($contents);
        // $count = count($contents_unique);
        $attempts = DB::table('scormvars_master')->count();
        $passed =   DB::table('scormvars_master')->where('lesson_status','completed')->count();
        $overview = 1;
        return view('training.reports.scorms.scorms_report',compact('count','attempts','passed','overview'));
    }


    public function scormsReportScorms()
    {
        $contents = DB::table('scormvars_master')->pluck('content_id')->toArray();
        $contents_unique = array_unique($contents);
        $scorms = Content::whereIn('id',$contents_unique);
        $type = 'all';
        $course = '';
        if(isset(request()->course_id))
        {
            $type = 'course';
            $scorms = $scorms->where('course_id',request()->course_id);
            $course = Course::where('id',request()->course_id)->first();
            // dd($course);
        }


        $scorms = $scorms->get();
        // dd($scorms);
        return view('training.reports.scorms.scorms_report',compact('scorms','type','course'));
    }


    public function scorm_users()
    {

        $scorm_id = request()->id;
        // $completed_courses  = DB::table('user_groups')
        // ->join('course_groups', function ($join) use($group_id) {
        //     $join->on('course_groups.group_id', '=', 'user_groups.group_id')
        //          ->where('user_groups.group_id',$group_id)
        // })
        // ->join('courses_registration as cr2','cr2.user_id','user_groups.user_id')
        // ->count('cr2.course_id');

        $users = DB::table('scormvars_master')
                            ->join('users', function ($join) {
            $join->on('scormvars_master.user_id', '=', 'users.id');
        })->where('content_id',$scorm_id)->get();
        // dd($users);
        // foreach($users_scorms as $sc)
        //     $users_scorms_arr[] = $sc->user_id;
        // $users = User::whereIn('id',$users_scorms_arr)->get();
        // dd($users);
        return view('training.reports.scorms.users',compact('users'));
    }



    public function coursesReportOverview()
    {
        $course_id = request()->id;
        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);
        $user_id = request()->user_id??null;
        $show_all = request()->show_all??1;
        $user = '';
        if(!is_null($user_id))
        {
            $user = User::getUser($user_id);
        }



        $assigned_learners =  CourseRegistration::getAssigned(512)->where('course_id',$course_id)->count();
        $assigned_instructors = CourseRegistration::getAssigned(511)->where('course_id',$course_id)->count();
        $completed_learners =  CourseRegistration::getAssigned(512)->whereRaw('courses_registration.progress >= courses.complete_progress')
                                                    ->where('course_id',$course_id)->count();
        $learners_in_progress =  CourseRegistration::getAssigned(512)->whereRaw('courses_registration.progress < courses.complete_progress')
                                                    ->where('courses_registration.progress','>',0)
                                                    ->where('course_id',$course_id)->count();
        $learners_not_started = CourseRegistration::getAssigned(512)->where('courses_registration.progress',0)->where('course_id',$course_id)->count();
        $count = 1;
        $overview = 1;

        if(isset(request()->export))
        {
            $from_user = $this->coursesReportUserFromSql(null,1);
            $from_test  = $this->coursesReportTestFromSql(null,1);
            $from_scorm  = $this->coursesReportScormFromSql(null,1);
            $from_assessment  = $this->coursesAssessmentsFromSql(null,1);
            // dd($learners_in_progress);
            return Excel::download(new CourseOverviewExport($assigned_learners,$completed_learners,$learners_in_progress,$learners_not_started,$assigned_instructors,$from_user,$from_test,$from_scorm,$from_assessment,$course), 'CourseOverview.xlsx');
        }

        return view('training.reports.courses.course_report',compact('completed_learners', 'course_id', 'overview'
        , 'assigned_learners', 'count', 'assigned_instructors', 'course','show_all','user'));
    }

    public function coursesReportUser()
    {
        $course_id = request()->id;

        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);
        $training_option_id = $course[0]->training_option_id;
        $branch_id = getCurrentUserBranchData()->branch_id;
        $show_all = request()->show_all??1;
        $user_id = request()->user_id??null;
        $user = '';
        if(!is_null($user_id))
        {
            $user = User::getUser($user_id);
        }

        $select = ' select users.id,users.email,user_branches.name,courses_registration.progress,roles.role_type_id,sessions.date_from,sessions.date_to,constants.name as c_name ,courses.complete_progress,courses_registration.id as c_reg_id,courses_registration.created_at as enrolled_date ,users.last_login as last_login';
        $from = $this->coursesReportUserFromSql($user_id,$show_all);

        $sql = $select.$from;

        if(!is_null($user_id) && $show_all==0)
        {
            $users = DB::select($sql, [$branch_id,$branch_id, $course_id,$user_id]);
        }
        else
        {
            $users = DB::select($sql, [$branch_id,$branch_id, $course_id]);
        }

        $paginator = Paginator::GetPaginator($users);
        $users = $paginator->items();
        $count = $paginator->total();

        if(isset(request()->export))
        {
            return Excel::download(new UsersExport($from,$course_id,$training_option_id,$user_id,$show_all), 'Users.xlsx');

        }
        // dd($show_all);
        return view('training.reports.courses.course_report',compact('course_id', 'users', 'course','paginator','count','user','show_all'));

    }

    public function coursesReportTest()
    {

        $course_id = request()->id;
        $user_id = request()->user_id??null;
        $user = '';
        if(!is_null($user_id))
        {
            $user = User::getUser($user_id);
        }
        $show_all = request()->show_all??1;

        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);

        $select = " select distinct contents.id as content_id, contents.title as content_title,exams.id as exam_id,c2.title as section ,
        (select count(DISTINCT status,user_id) from user_exams where status= 1 and  exam_id= exams.id) as completed ,
        (select count(DISTINCT status,user_id) from user_exams where status= 1 and  exam_id= exams.id and mark >= (exams.pass_mark/100*exams.exam_mark)) as passess";
        $from = $this->coursesReportTestFromSql($user_id,$show_all);
        $sql = $select.$from;
        // dd($sql);
        if(!is_null($user_id) && $show_all == 0)
        {
            $tests = DB::select($sql,[$user_id,1,$course_id]);
        }
        else
        {
            $tests = DB::select($sql,[$course_id]);
        }


        $paginator = Paginator::GetPaginator($tests);
        $tests = $paginator->items();
        $count = $paginator->total();

        if(isset(request()->export))
        {
            return Excel::download(new CoursesTestsExport($from,$course_id,$user_id,$show_all), 'Tests_'.$course_id.'.xlsx');
        }

        return view('training.reports.courses.course_report',compact('course_id', 'tests', 'course','paginator','count','show_all','user'));

    }

    public function coursesReportScorm()//new
    {
        $course_id = request()->id;
        $user_id = request()->user_id??null;

        $user = '';
        if(!is_null($user_id))
        {
            $user = User::getUser($user_id);
        }
        $show_all = request()->show_all??1;

        $select = " SELECT i.content_id,i.course_id,i.title,i.attempts,other.passess,i.sestion ";
        $from = $this->coursesReportScormFromSql($user_id,$show_all);
        $sql = $select.$from;
        // dump($course_id);
        // dd($sql);
        if(!is_null($user_id) && $show_all == 0)
        {
            $scorms = DB::select($sql,[$course_id,$course_id,$course_id,$user_id ]);
        }
        else
        {
            $scorms = DB::select($sql,[$course_id,$course_id ]);
        }


        $paginator = Paginator::GetPaginator($scorms);
        $scorms = $paginator->items();
        $count = $paginator->total();

        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);
        if(isset(request()->export))
        {
            return Excel::download(new CoursesScormsExport($from,$course_id, $user_id,$show_all), 'Scorms_'.$course_id.'.xlsx');
        }
        return view('training.reports.courses.course_report',compact('scorms','course','course_id','paginator','count','show_all','user'));
    }


    public function coursesAssessments()
    {
        $course_id = request()->id;
        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);
        $training_option_id = $course[0]->training_option_id;
        $branch_id = getCurrentUserBranchData()->branch_id;
        $sessions = Session::where('course_id',$course_id)->get();

        $user_id = request()->user_id??null;
        $user = '';
        if(!is_null($user_id))
        {
            $user = User::getUser($user_id);
        }
        // dd($user);
        $show_all = request()->show_all??1;

        $select = "select pre.user_id, pre.name user_name,pre.mark pre_mark, post.mark post_mark,pre.content_id, post.content_id,
        if(post.mark is Null or post.mark = '','Not Yet',if(pre.mark<post.mark,'Improved',if(pre.mark=post.mark,'Constant','Deceased'))) knowledge_status,
        pre.attendance_count,trainer.name trainer_name,pre.email user_email,pre.s_id";
        $from = $this->coursesAssessmentsFromSql($user_id,$show_all);

        $sql = $select.$from;

        if(isset(request()->session_id))
        {
            $session_id = request()->session_id;
            if(!is_null($user_id) && $show_all == 0)
            {
                $assessments = DB::select($sql, [$course_id, $course_id,$session_id,$user_id,$branch_id,512,$branch_id,513,514,$course_id,$course_id,$session_id,$branch_id,512,$branch_id,514,511,$branch_id,$session_id,$branch_id]);
            }
            else
            {
                $assessments = DB::select($sql, [$course_id, $course_id,$session_id,$branch_id,512,$branch_id,513,514,$course_id,$course_id,$session_id,$branch_id,512,$branch_id,514,511,$branch_id,$session_id,$branch_id]);
            }

        }
        else
        {
            $session_id = '';
            if(!is_null($user_id) && $show_all == 0)
            {
                $assessments = DB::select($sql, [$course_id, $course_id,$user_id,$branch_id,512,$branch_id,513,514,$course_id,$course_id,$branch_id,512,$branch_id,514,511,$branch_id,$branch_id]);
            }
            else
            {
                $assessments = DB::select($sql, [$course_id, $course_id,$branch_id,512,$branch_id,513,514,$course_id,$course_id,$branch_id,512,$branch_id,514,511,$branch_id,$branch_id]);
            }

        }


        if(isset(request()->export))
        {
            return Excel::download(new AssessmentExport($from,$course_id,$session_id,$training_option_id,$user_id,$show_all), 'Assessments.xlsx');
        }

        $paginator = Paginator::GetPaginator($assessments);
        $assessments = $paginator->items();
        $count = $paginator->total();

        $session_id = request()->session_id??'';
        return view('training.reports.courses.course_report',compact('course_id', 'assessments', 'course','sessions','paginator','count','session_id','user','show_all'));

    }


    public function progressDetails()
    {

        session()->put('active_sidebar_route_name',-1);

        $user_id = request()->user_id;
        $course_id = request()->course_id;
        $back_page = request()->back_page??'courses';

        // clear session => (Sidebar active color)
        session()->put('Infrastructure_id', -1);

        $course_registration = CourseRegistration::where('course_id', $course_id)
        ->where('user_id', $user_id)
        ->select('id', 'role_id', 'progress')
        ->first();
        // dd($course_registration);
        $role_id = -1;
       // Get Course With Contents

        $course = $this->getCourseWithContents($course_id, $role_id,$user_id);

        // validate if course exists or not
        if(!$course){
            abort(404);
        }// end if

        // Get total rate for course
        $total_rate = app('App\Http\Controllers\Front\CourseController')->getTotalRateForCourse($course->id);

        // Get User Course Activities
        $activities = app('App\Http\Controllers\Front\CourseController')->getUserCourseActivities($course->id, $user_id);
        $user   = User::where('id',$user_id)->first();
        // dd($user);
        return view('training.reports.courses.progress_details',compact('course', 'total_rate', 'activities', 'course_registration', 'role_id','user','back_page'));
        // return view('pages.course_details',compact('course', 'total_rate', 'activities', 'course_registration', 'role_id'));
    }

    public function exam(){

        $content_id = request()->content_id;
        // dd($content_id);
        $user_id = request()->user_id;
        $back_page = request()->back_page??'progress_details';

        // Get Exam Content
        $exam = Content::whereId($content_id)
            ->with(['section','course','exam' => function($q){
                return $q->with(['users_exams' => function($q){
                    return $q->where('user_id', request()->user_id);
                }]);
            }])
            ->whereHas('course',function ($q){
                $q->where('branch_id',getCurrentUserBranchData()->branch_id);
            })
            ->first();

        if (!$exam){
            abort(404);
        }

        $user_course_register = CourseRegistration::where('course_id',$exam->course->id)->with(['role','register_user','course'])
        ->where('user_id',$user_id)
        ->first();

        // Validate User Course Registration And Exam is Exists Or Not
        if( !$user_course_register || (!$exam->exam) ){
            abort(404);
        }


        $role_id =  $user_course_register->role_id;
        // Get Course With Contents
        // dd($exam->course->id);
        $course = Course::where('id',$exam->course->id)->first();
        $user   = User::where('id',$user_id)->first();
        return view('training.reports.courses.exam',compact('exam','course','user','back_page'));
        // return view('pages.exam',compact('exam','course'));
    } // end function


    public function exam_review(){

        $exam_id = request()->exam_id;
        $user_id = request()->user_id;
        $course_id = request()->course_id;
        $back_page = request()->back_page;

        $page_type = 'review';

        // Get UserExam (Attempt) Data
        $exam = UserExam::whereId($exam_id)
            ->where('user_id',$user_id)
            ->where('status',1)
            ->with(['exam.content.questions.answers','user_answers','user_questions'])
            ->whereHas('exam.content.course',function ($q){
                $q->where('branch_id',getCurrentUserBranchData()->branch_id);
            })
            ->first();

        if (!$exam){
            abort(404);
        }

        // Get next and prev
        $arr = CourseContentHelper::nextAndPreviouseQuery($exam->exam->content->course_id,$exam->exam->content->id,$exam->exam->content->order,$exam->exam->content->parent_id,$exam->exam->content->section->order);
        $previous = $arr[0];
        $next = $arr[1];
        $next = ($next[0]??null);
        $previous = ($previous[0]??null);


        $course = Course::where('id',$course_id)->first();
        $user   = User::where('id',$user_id)->first();
        // end next and prev
        return view('pages.review',compact('exam','page_type','next','previous','course','user','back_page'));

    } // end function

    public function exam_result_details (){
        // Get UserExam (Attempt) Data
        $user_exams_id = request()->user_exams_id;
        $user_id = request()->user_id;
        $course_id = request()->course_id;
        $back_page = request()->back_page;

        $exam = UserExam::whereId($user_exams_id)
            ->where('user_id',$user_id)
            ->where('status',1)
            ->with(['exam' => function($q){
                return $q->select('id','content_id')->with(['content' => function($query){
                    $query->select('id','title');
                }]);
            }])
            ->whereHas('exam.content.course',function ($q){
                $q->where('branch_id',getCurrentUserBranchData()->branch_id);
            })
            ->first();

        // Check If Attempt Is Exists
        if ( !$exam  ) abort(404);

        $exam_title =  $exam->exam->content->title;
        $exam_id =  $exam->exam->content->id;

        $unit_marks =  DB::select(DB::raw("
            SELECT units.title as unit_title, questions.unit_id as unit_id , SUM(user_questions.mark) as unit_marks , SUM(questions.mark) as total_marks
            from questions
            left join user_questions ON user_questions.question_id = questions.id
            and user_questions.user_exam_id = $user_exams_id
            LEFT JOIN units ON questions.unit_id = units.id
            where questions.exam_id = $exam_id
            GROUP BY questions.unit_id
       "));

        $units_rprt = DB::select(" SELECT m.id, m.title, sum(m.res) as result ,count(m.question_id) as count,sum(m.tot) as total
           from (
           SELECT u.id, u.title, qu.question_id
           , (select uq.mark/count(id) from question_units where question_id = qu.question_id) as res
            , (select q.mark/count(id) from question_units where question_id = qu.question_id) as tot
           , uq.mark
           from units u
           join question_units qu on u.id=qu.unit_id
           join user_questions uq on uq.question_id = qu.question_id
           join user_exams ue on ue.id = uq.user_exam_id
           join questions q on q.id = uq.question_id
           where  ue.id = $user_exams_id
           order by u.id asc
                ) as m
                group by m.id
                union
            SELECT  ' ' as id, 'Other' as title,sum(uq.mark) as result,count(uq.question_id) as count,sum(q.mark)
            from user_questions uq  join user_exams ue on ue.id = uq.user_exam_id
            join questions q on q.id = uq.question_id
            where  ue.id = $user_exams_id and uq.question_id not in (select question_id from question_units)
            ") ;
         $course = Course::where('id',$course_id)->first();
         $user   = User::where('id',$user_id)->first();
        return view('pages.exam_details',compact('unit_marks','exam_title','exam_id','units_rprt','user','course','back_page'));
    } // end function

    public function groupReportOverview()
    {

        $group_id = request()->id;
        $count =1;
        $assigned_users     = DB::table('user_groups')->where('group_id',$group_id)->count(DB::raw('DISTINCT user_id'));
        $assigned_courses   = DB::table('course_groups')->where('group_id',$group_id)->count(DB::raw('DISTINCT course_id'));
        $completed_courses  = DB::table('user_groups')
        ->join('course_groups', function ($join) use($group_id) {
            $join->on('course_groups.group_id', '=', 'user_groups.group_id')
                    ->where('user_groups.role_id',3)
                    ->where('user_groups.group_id',$group_id)
                    ->where('course_groups.group_id',$group_id);
        })
        ->join('courses_registration as cr1', function ($join) {
            $join->on('cr1.course_id', '=', 'course_groups.course_id')
                    ->where('cr1.progress',100);
        })
        ->join('courses_registration as cr2','cr2.user_id','user_groups.user_id')
        ->count(DB::raw('DISTINCT cr1.id'));
        $overview = 1;
        return view('training.reports.groups.group_report',compact('group_id','overview','assigned_users','count','assigned_courses','completed_courses'));

    }

    public function groupsReportUser()
    {

        $group_id = request()->id;
        // dd($group_id);
        $users   = DB::table('user_groups')
                        ->join('users','users.id','user_groups.user_id')
                        ->join('model_has_roles','model_has_roles.model_id','users.id')
                        ->join('roles','roles.id','model_has_roles.role_id')
                        ->where('user_groups.group_id',$group_id)
                        ->select('users.id','users.name','roles.name as role_name','users.last_login')
                        ->get();
        // dd($users);
        return view('training.reports.groups.group_report',compact('group_id','users'));

    }

    public function groupsReporcourse()
    {

        $group_id = request()->id;
        $courses  = DB::table('course_groups')
                        ->join('courses','courses.id','course_groups.course_id')
                        ->where('course_groups.group_id',$group_id)
                        ->select('courses.id','courses.title','courses.PDUs')
                        ->get();
        // dd($courses);
        return view('training.reports.groups.group_report',compact('group_id','courses'));

    }


    public function preview_content(){
        $content_id = request()->content_id;
        $user_id = request()->user_id;
        $preview_gate_allows = Gate::allows('preview-gate');


        // Get content from DB
        $content = Content::whereId($content_id)
            ->with(['upload','course' ,'section.gift','user_contents' => function($q){
                $q->where('user_id',request()->user_id);
            }])->whereHas('course',function ($q){
                $q->where('branch_id',getCurrentUserBranchData()->branch_id);
            })->first();

        // Check if content not found => ABORT(404)
        if (!$content){
            abort(404);
        }// end if

        // Get next and prev
        $arr = CourseContentHelper::NextAndPreviouseNavigation($content);
        $next = $arr['next'];
        $previous = $arr['previous'];
        // end next and prev

        $enabled = true;

       // if downloadable status == true (Download file)
        if($content->downloadable == 1){
            // get content file path
            $file = $this->getContentFilePath($content->post_type);
            if ($file){
                return response()->download($file); // download response
            }
        }// end if



        // get time limit content (duration in seconds)
        $time_limit = $content->time_limit;

        $popup_gift_status = false;

        $page_num = UserContentsPdf::where('content_id',$content->id)->where('user_id',request()->user_id)->pluck('current_page')->first();

        $flag = 0;
        $role_id = -1;
        // Get Course With Contents
        $course = $this->getCourseWithContents($content->course->id, $role_id,$user_id);

        return view('training.reports.courses.preview_content',compact('content','previous','next','enabled','time_limit','popup_gift_status','page_num','flag','course'));
        // return view('pages.file', compact('content','previous','next','enabled','time_limit','popup_compelte_status','popup_gift_status','page_num','flag','course'));
    } //



    public function getCourseWithContents($course_id, $role_id,$user_id){

        $course = Course::where('id', $course_id)->where('branch_id',getCurrentUserBranchData()->branch_id);


        $course = $course->whereHas('users', function ($q) use ($user_id){
            $q->where('users.id', $user_id);
        })->with(['users' => function($query) use ($user_id){
            $query->where('user_id', $user_id);
        }, 'course_rate' => function($query) use ($user_id){
            return $query->where('user_id', $user_id);
        }]);


        $course = $course->with(['uploads' => function($query) {
            return $query->where(function ($q){
                $q->where('post_type','intro_video')->orWhere('post_type', 'image');
            });
        }, 'contents' => function($query) use($role_id,$user_id){
            $query->where('parent_id',null)->with(['gift','details',
                'contents' => function($q){
                    return $q->orderBy('order');
                },
                'contents.details','contents.user_contents' => function($q) use($user_id) {
                    return $q->where('user_id', $user_id);
                }])
            ->orderBy('order');

            if($role_id != -1){
                $role = \App\Models\Training\Role::where('id',$role_id)->first();
                if ($role->role_type_id == 512){
                    $query->where('hide_from_trainees', 0);
                }
            }
        }])->first();
        // dd($course);
        return $course;
    } // end function


    public function preview_discussion(){

        $type = 'message';
        if (request()->has('type')){
            $type = request()->type;
        }
        $id = request()->id;
        $user_id = request()->user_id;
        $message = Message::where('id',$id)->with(['course.course','user.branches' => function($q){
            $q->where('branch_id',getCurrentUserBranchData()->branch_id);
        },'replies.user.branches' => function($q){
            $q->where('branch_id',getCurrentUserBranchData()->branch_id);
        }])->where('type',$type)->first();

        if(!$message || !$message->course){
            abort(404);
        }

        $disabled_reply = false;
        $discussion_not_start = false;
        $user = User::where('id',$user_id)->first();// auth()->user();
        // dd($user);
        $user_role =  $user->roles()->first();

        if ($message->type == 'discussion'){
            $now = Carbon::now();
            $discussion = Discussion::with(['message'])->where('message_id',$message->id)->first();

            // check participants
            if ($user_role->id != 4 && $user_role->role_type_id != 510 && $user->id != $message->user_id){
                $user_course_registration =  CourseRegistration::where('user_id',$user->id)->where('course_id',$discussion->message->course_id)->first();
                if (!$user_course_registration){
                    abort(404);
                }
            }


            // check date
            if($discussion->start_date <= $now && $discussion->end_date > $now){

            }else{
                if ($discussion->start_date > $now && !(Gate::allows('preview-gate'))){
                    $discussion_not_start = true;
                }
                $disabled_reply = true;
            }

            $content = Content::where('id',$discussion->content_id)->first();
            if (!$content){
                abort(404);
            }

            // end next and prev
            return view('training.reports.courses.preview_discussion',compact('message','type','discussion','disabled_reply','discussion_not_start'));
            // return view('training.messages.reply',compact('message','type','discussion','disabled_reply','next','previous','discussion_not_start'));

        }
    }


    public function testUsers()
    {

        $exam_id = request()->exam_id;
        $content_id = request()->content_id;

        $sql_c = "select title,course_id,id from contents where id = ?";
        $content = DB::select($sql_c,[ $content_id]);

        $sql_co = "select title from courses where id = ?";
        $course = DB::select($sql_co,[ $content[0]->course_id]);


        $sql_exam = " select id,exam_mark,pass_mark from exams where id= ? ";
        $exam = DB::select($sql_exam,[$exam_id]);

        $select = " select mark,time,end_attempt ,users.id as user_id,user_branches.name as user_name,ue1.mark*100/exams.exam_mark  as score,exams.pass_mark,users.email,ue1.id as u_id,exams.exam_mark ";
        $from = "  from user_exams ue1 join users on users.id = ue1.user_id
                            join exams on exams.id = ue1.exam_id
                            join user_branches on user_branches.user_id = users.id and user_branches.branch_id = ? and user_branches.deleted_at is null
                    where exam_id = ? and status = ?
                    and (ue1.mark,ue1.user_id) = any (
                    select max(ue2.mark),ue2.user_id
                    from user_exams ue2
                    where exam_id = ? and status = ?
                    group by ue2.user_id
                    )";
        $sql = $select.$from;

        $users = DB::select($sql,[getCurrentUserBranchData()->branch_id,$exam_id,1,$exam_id,1]);

        $paginator = Paginator::GetPaginator($users);
        $users = $paginator->items();
        $count = $paginator->total();
        if(isset(request()->export))
        {
            return Excel::download(new testUsersExport($from,$exam_id), 'testUsers_.xlsx');
        }
        return view('training.reports.users.test_users',compact('users','paginator','count','content','course','exam'));

    }

    public function scormUsers()
    {


        $content_id = request()->content_id;
        $sql_c = "select title,course_id,id from contents where id = ?";
        $content = DB::select($sql_c,[ $content_id]);

        $sql_co = "select title from courses where id = ?";
        $course = DB::select($sql_co,[ $content[0]->course_id]);

        $select = " select users.id as user_id,user_branches.name as user_name,users.email,scormvars_master.date,scormvars_master.score, scormvars_master.lesson_status ";
        $from = " from scormvars_master  join users on users.id = scormvars_master.user_id
                            join user_branches on user_branches.user_id = users.id
                    where scormvars_master.deleted_at is null and scormvars_master.content_id = ? ";
        $sql = $select.$from;

        $users = DB::select($sql,[$content_id]);

        $paginator = Paginator::GetPaginator($users);
        $users = $paginator->items();
        $count = $paginator->total();

        if(isset(request()->export))
        {
            return Excel::download(new scormUsersExport($from,$content_id), 'usersScorms_.xlsx');
        }

        return view('training.reports.users.scorm_users',compact('users','paginator','count','content','course'));

    }

    private function usersReportCourseFromSql($course_id,$show_all)
    {
        $from = ' from courses_registration
        join roles on roles.id = courses_registration.role_id
                            and roles.deleted_at is null
                            and roles.branch_id = ?
                            and roles.role_type_id = ?
        join courses on courses.id = courses_registration.course_id
                            and courses.deleted_at is null
                            and courses.branch_id = ? ';
            if(!is_null($course_id) && $show_all == 0)
            {
                $from .=' and courses.id = ?';
            }

        $from .=' join users on users.id = courses_registration.user_id
            join user_branches on user_branches.user_id = users.id
                                and user_branches.deleted_at is null
                                and user_branches.branch_id = ?
            left join categories on categories.id = courses.category_id
            join constants on constants.id = courses.training_option_id
        where courses_registration.user_id = ?
        order by courses.id ';

        return $from;
    }

    private function usersReportTestFromSql($course_id,$show_all)
    {
        $from = " from contents join exams on exams.content_id = contents.id
        join user_exams on  user_exams.exam_id = exams.id
                and user_exams.user_id =  ?
        join courses on courses.id = contents.course_id
                and courses.branch_id = ? ";
        if(!is_null($course_id) && $show_all == 0)
        {
            $from .= 'and courses.id = ? ';
        }

        $from .= "join courses_registration on courses.id  = courses_registration.course_id
                    and courses_registration.user_id = ?
            join roles on  roles.id = courses_registration.role_id
                    and  roles.role_type_id = ?
                    and roles.deleted_at is null
                    and roles.branch_id = ?
        order by courses.id,user_exams.time   ";
        return $from;
    }

    private function usersReportScormFromSql($course_id,$show_all)
    {
        $from = "from scormvars_master   join users on scormvars_master.user_id = users.id
                                and scormvars_master.user_id = ?
                        join contents on contents.id = scormvars_master.content_id
                                and contents.deleted_at is null
                        join courses on courses.id = contents.course_id and courses.branch_id = ?
                                and courses.deleted_at is null";
        if(!is_null($course_id) && $show_all == 0)
            $from .= " and courses.id = ? ";

        $from .= " where scormvars_master.deleted_at is  null
                    order by courses.id ";
        return $from;
    }

    public function coursesReportUserFromSql($user_id,$show_all)
    {
        $from = ' from courses_registration
                join roles on roles.id = courses_registration.role_id
                                    and roles.deleted_at is null
                                    and roles.branch_id = ?
                join constants on constants.id = roles.role_type_id
                join users on users.id = courses_registration.user_id
                join user_branches on users.id = user_branches.user_id and user_branches.branch_id = ?
                join courses on courses.id = courses_registration.course_id
                left join sessions on sessions.id = courses_registration.session_id
            where courses_registration.course_id = ? ';
        if(!is_null($user_id) && $show_all == 0)
        {
        $from .= ' and users.id = ?' ;
        }
        $from .= ' order by users.id ';
        return $from;
    }


    public function coursesReportTestFromSql($user_id,$show_all)
    {
        $from = "   from contents
                        join exams on exams.content_id = contents.id
                        join courses on courses.id = contents.course_id
                        join contents  c2 on contents.parent_id = c2.id ";
        if(!is_null($user_id) && $show_all == 0)
        {
            $from  .= " join user_exams on user_exams.exam_id = exams.id and user_exams.user_id = ?  and user_exams.status = ? ";
        }
        $from  .= " where courses.id = ? and contents.deleted_at is null ";
        return $from;
    }

    public function coursesReportScormFromSql($user_id,$show_all)
    {
        $from = "  FROM
                    (
                        select count(content_id) attempts,scormvars_master.user_id,scormvars_master.content_id,scormvars_master.course_id,contents.title,c2.title as sestion
                        from `contents`
                        join scormvars_master on contents.id = scormvars_master.content_id
                                    and scormvars_master.course_id = ?
                                    and contents.deleted_at is null
                                    and scormvars_master.deleted_at is null ";
        $from .= "     join contents  c2 on contents.parent_id = c2.id
                        group by scormvars_master.course_id ,scormvars_master.content_id,contents.title,c2.title

                    ) i
                    left join
                    (
                        select count(content_id) passess,scormvars_master.content_id
                        from `contents` join scormvars_master on contents.id = scormvars_master.content_id
                                    and scormvars_master.course_id = ? ";

        $from .= "      where lesson_status = 'completed'
                        group by scormvars_master.course_id ,scormvars_master.content_id,contents.title
                    ) other on other.content_id = i.content_id
                    ";
        if(!is_null($user_id) && $show_all == 0)
        {
            $from .= " join scormvars_master s on s.content_id = i.content_id
                        and  s.course_id = ? and s.user_id = ?
                            and s.deleted_at is null  ";
        }
        return $from;
    }

    public function coursesAssessmentsFromSql($user_id,$show_all)
    {
        $from = ' from
        (
            SELECT max(user_exams.mark) mark, user_exams.user_id user_id,user_branches.name name
                    , exams.content_id content_id,courses_registration.attendance_count attendance_count,
                    courses_registration.session_id,users.email,
                    concat("SID :",sessions.id,"|",sessions.date_from,"|",sessions.date_to) as s_id
            FROM user_exams
            join exams on user_exams.exam_id = exams.id
            join contents on contents.id = exams.content_id  and contents.course_id = ?
            join courses on courses.id = contents.course_id
            join courses_registration on user_exams.user_id = courses_registration.user_id
                                        and courses_registration.course_id = ? ';
            if(isset(request()->session_id))
                $from .= 'and courses_registration.session_id = ? ';
            $from .= '
            left join sessions on sessions.id = courses_registration.session_id
            join users on users.id = user_exams.user_id ';
            if(!is_null($user_id) && $show_all == 0)
            {
                $from .= ' and users.id = ? ';
            }
            $from .= '
            join  user_branches on  user_branches.user_id = users.id and user_branches.branch_id = ?
            join  roles on  roles.id = courses_registration.role_id
                            and roles.role_type_id = ? and roles.deleted_at is Null
                            and roles.branch_id = ?
            where exams.exam_type = ?
            group by user_exams.user_id, exams.content_id,user_branches.name,courses_registration.attendance_count,
            courses_registration.session_id,users.email,sessions.id
        ) pre
        left join (
            SELECT max(user_exams.mark) mark, user_exams.user_id user_id
                    , exams.content_id  content_id,user_branches.name name
            FROM user_exams
            join exams on user_exams.exam_id = exams.id and exams.exam_type = ?
            join contents on contents.id = exams.content_id   and contents.course_id = ?
            join courses on courses.id = contents.course_id
            join courses_registration on user_exams.user_id = courses_registration.user_id
                                        and courses_registration.course_id = ? ';
            if(isset(request()->session_id))
                $from .= 'and courses_registration.session_id = ? ';
            $from .= '
            join users on users.id = user_exams.user_id
            join  user_branches on  user_branches.user_id = users.id and user_branches.branch_id = ?
            join  roles on  roles.id = courses_registration.role_id
                            and roles.role_type_id = ? and roles.deleted_at is Null
                            and roles.branch_id = ?
            where exams.exam_type = ?
            group by user_exams.user_id, exams.content_id,user_branches.name
        ) post on pre.user_id = post.user_id
        left join
        (
            SELECT courses_registration.session_id, GROUP_CONCAT(user_branches.name) name
            FROM roles
            join courses_registration on courses_registration.role_id = roles.id
                                and roles.role_type_id = ?
                                and roles.deleted_at is Null and roles.branch_id = ? ';
            if(isset(request()->session_id))
                $from .= ' and courses_registration.session_id = ? ';
            $from .= '
            join users on users.id = courses_registration.user_id
            join  user_branches on  user_branches.user_id = users.id and user_branches.branch_id = ?
            GROUP BY courses_registration.session_id
        ) trainer on trainer.session_id  = pre.session_id
        order by pre.session_id  ';
        return $from;
    }

}
