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
use App\Exports\UsersExport;
use App\Exports\AssessmentExport;
use App\Exports\CoursesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\Models\Training\UserExam;
use App\Helpers\CourseContentHelper;
use App\Models\Paginator;


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
        // dd($user_id);
        // $user = User::findOrFail($user_id);
        $sql = "SELECT users.id,users.email,user_branches.name
                FROM users  join user_branches on users.id = user_branches.user_id
                where users.id = ? " ;
        $user = DB::select($sql,[$user_id]);
        // dd($user);
        $learners_no         = 1;
        $complete_courses_no =  CourseRegistration::getCoursesNo()
                                    ->where('courses_registration.user_id',$user_id)
                                    ->where('courses_registration.progress',100)->count();
        $courses_in_progress =  CourseRegistration::getCoursesNo()
                                    ->where('courses_registration.progress','<',100)
                                    ->where('courses_registration.progress','>',0)
                                    ->where('courses_registration.user_id',$user_id)->count();
        $courses_not_started = CourseRegistration::getCoursesNo()
                                    ->where('courses_registration.progress',0)
                                    ->where('courses_registration.user_id',$user_id)->count();
        $overview = 1;

        return view('training.reports.users.user_report',compact('user_id','learners_no','complete_courses_no',
            'courses_in_progress','courses_not_started','overview', 'user'));
    }

    public function usersReportCourse()
    {
        $user_id = request()->id;
        $sql = "SELECT users.id,users.email,user_branches.name
        FROM users  join user_branches on users.id = user_branches.user_id
        where users.id = ? " ;
        $user = DB::select($sql,[$user_id]);
        $branch_id = getCurrentUserBranchData()->branch_id;
        // $courses = CourseRegistration::getCoursesNo()->where('courses_registration.user_id',$user_id)->get();
        // dd($courses);
        $select = 'select courses.id,courses.title,courses_registration.progress,courses_registration.score,courses.created_at,courses.PDUs,courses.complete_progress,courses_registration.id as c_reg_id ';

        $from = ' from courses_registration
                    join roles on roles.id = courses_registration.role_id
                                        and roles.deleted_at is null
                                        and roles.branch_id = ?
                                        and roles.role_type_id = 512
                    join courses on courses.id = courses_registration.course_id
                                        and courses.deleted_at is null
                                        and courses.branch_id = ?
                    join users on users.id = courses_registration.user_id
                    join user_branches on user_branches.user_id = users.id
                                        and user_branches.deleted_at is null
                                        and user_branches.branch_id = ?
                where courses_registration.user_id = ?
                order by users.id ';

        $sql2 = $select.$from;
        $courses = DB::select($sql2, [$branch_id, $branch_id, $branch_id, $user_id]);
        $paginator = Paginator::GetPaginator($courses);
        $courses = $paginator->items();
        $count = $paginator->total();
        if(isset(request()->export))
        {
            return Excel::download(new CoursesExport($from, $user_id), 'Courses.xlsx');
        }

        return view('training.reports.users.user_report',compact('courses', 'user','paginator','count'));
    }



    public function usersReportTest()
    {
        $user_id = request()->id;
        $sql = "SELECT users.id,users.email,user_branches.name
                FROM users  join user_branches on users.id = user_branches.user_id
                where users.id = ? " ;
        $user = DB::select($sql,[$user_id]);

        $branch_id = getCurrentUserBranchData()->branch_id;
        $sql2 = "select user_exams.id, contents.title as content_title, courses.title as course_title, user_exams.time
                        , exams.exam_mark, exams.pass_mark, user_exams.mark as exam_trainee_mark, user_exams.status,
                        courses.id as course_id,contents.id as content_id
                from contents   join exams on exams.content_id = contents.id
                                join user_exams on  user_exams.exam_id = exams.id
                                        and user_exams.user_id =  ?
                                join courses on courses.id = contents.course_id
                                        and courses.branch_id = ?
                                join courses_registration on courses.id  = courses_registration.course_id
                                        and courses_registration.user_id = ?
                                join roles on  roles.id = courses_registration.role_id
                                        and  roles.role_type_id = ?
                                        and roles.deleted_at is null
                                        and roles.branch_id = ?
                order by user_exams.time   ";

        $tests = DB::select($sql2, [$user_id, $branch_id, $user_id, 512, $branch_id]);
        $paginator = Paginator::GetPaginator($tests);
        $tests = $paginator->items();
        $count = $paginator->total();

        return view('training.reports.users.user_report',compact('user_id', 'tests', 'user','paginator','count'));
    }

    public function usersReportScorm()
    {

        $branch_id = getCurrentUserBranchData()->branch_id;
        $user_id = request()->user_id;
        $sql = "SELECT users.id,users.email,user_branches.name
                FROM users  join user_branches on users.id = user_branches.user_id
                where users.id = ? " ;
        $user = DB::select($sql,[$user_id]);
        // dd($user);s
        $sql2 = " select contents.id,courses.title as crtitle,contents.title as cotitle,scormvars_master.date,
                        scormvars_master.score,scormvars_master.lesson_status
                from scormvars_master   join users on scormvars_master.user_id = users.id
                                                and scormvars_master.user_id = ?
                                        join contents on contents.id = scormvars_master.content_id
                                        join courses on courses.id = contents.course_id and courses.branch_id = ?
                ";
        $scorms = DB::select($sql2, [$user_id,$branch_id]);
        $paginator = Paginator::GetPaginator($scorms);
        $scorms = $paginator->items();
        $count  = $paginator->total();

        return view('training.reports.users.user_report',compact('user_id', 'scorms','user','paginator','count'));


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
        // $assigned_learners = DB::table('courses_registration')->where('course_id',$course_id)->where('role_id',3)->count();
        // $assigned_instructors = DB::table('courses_registration')->where('course_id',$course_id)->where('role_id',2)->count();
        // $completed_learners = DB::table('courses_registration')->where('role_id',3)->where('progress',100)->count();

        $assigned_learners1 = CourseRegistration::getAssigned(512);
        $assigned_learners =  $assigned_learners1->where('course_id',$course_id)->count();

        $assigned_instructors = CourseRegistration::getAssigned(511);
        $assigned_instructors =  $assigned_instructors->where('course_id',$course_id)->count();

        $completed_learners =  $assigned_learners1->where('progress',100)->where('course_id',$course_id)->count();

        $count = 1;
        $overview = 1;
        return view('training.reports.courses.course_report',compact('completed_learners', 'course_id', 'overview'
        , 'assigned_learners', 'count', 'assigned_instructors', 'course'));
    }

    public function coursesReportUser()
    {
        $course_id = request()->id;
        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);
        $branch_id = getCurrentUserBranchData()->branch_id;

        $select = ' select users.id,users.email,user_branches.name,courses_registration.progress,roles.role_type_id,sessions.date_from,sessions.date_to,constants.name as c_name ';

        $from = ' from courses_registration
                    join roles on roles.id = courses_registration.role_id
                                        and roles.deleted_at is null
                                        and roles.branch_id = ?
                    join constants on constants.id = roles.role_type_id
                    join users on users.id = courses_registration.user_id
                    join user_branches on users.id = user_branches.user_id and user_branches.branch_id = ?
                    join courses on courses.id = courses_registration.course_id
                    left join sessions on sessions.id = courses_registration.session_id
                where courses_registration.course_id = ?
                order by users.id ';

        $sql = $select.$from;

        $users = DB::select($sql, [$branch_id,$branch_id, $course_id]);
        $paginator = Paginator::GetPaginator($users);
        $users = $paginator->items();
        $count = $paginator->total();

        if(isset(request()->export))
        {
            return Excel::download(new UsersExport($from,$course_id), 'Users.xlsx');
        }

        return view('training.reports.courses.course_report',compact('course_id', 'users', 'course','paginator','count'));

    }

    public function coursesReportTest()
    {

        $course_id = request()->id;

        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);

        $sql = "select exams.id , contents.title as content_title
                from contents
                    join exams on exams.content_id = contents.id
                    join courses on courses.id = contents.course_id
                where courses.id = ? ";

        $tests = DB::select($sql,[$course_id]);

        $paginator = Paginator::GetPaginator($tests);
        $tests = $paginator->items();
        $count = $paginator->total();

        return view('training.reports.courses.course_report',compact('course_id', 'tests', 'course','paginator','count'));

    }

    public function coursesAssessments()
    {
        $course_id = request()->id;
        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);
        $branch_id = getCurrentUserBranchData()->branch_id;
        $sessions = Session::where('course_id',$course_id)->get();

        $select = "select pre.user_id, pre.name user_name,pre.mark pre_mark, post.mark post_mark,pre.content_id, post.content_id,
        if(pre.mark<post.mark,'Improved',if(pre.mark=post.mark,'Constant','Deceased')) knowledge_status,
        pre.attendance_count,trainer.name trainer_name,pre.email user_email,pre.s_id";
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
            join sessions on sessions.id = courses_registration.session_id
            join users on users.id = user_exams.user_id
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

        $sql = $select.$from;

        if(isset(request()->session_id))
        {
            $session_id = request()->session_id;
            $assessments = DB::select($sql, [$course_id, $course_id,$session_id,$branch_id,512,$branch_id,513,514,$course_id,$course_id,$session_id,$branch_id,512,$branch_id,514,511,$branch_id,$session_id,$branch_id]);
        }
        else
        {
            $session_id = '';
            $assessments = DB::select($sql, [$course_id, $course_id,$branch_id,512,$branch_id,513,514,$course_id,$course_id,$branch_id,512,$branch_id,514,511,$branch_id,$branch_id]);
        }


        if(isset(request()->export))
        {
            return Excel::download(new AssessmentExport($from,$course_id,$session_id), 'Assessments.xlsx');
        }

        $paginator = Paginator::GetPaginator($assessments);
        $assessments = $paginator->items();
        $count = $paginator->total();


        return view('training.reports.courses.course_report',compact('course_id', 'assessments', 'course','sessions','paginator','count'));

    }


    public function coursesReportScorm()//new
    {
        $course_id = request()->id;
        $sql = "SELECT i.id,i.content_id,i.course_id,i.lesson_status,i.title,i.attempts,other.passess
        FROM
        (
            select count(content_id) attempts,scormvars_master.id id ,scormvars_master.user_id,scormvars_master.content_id,scormvars_master.course_id,scormvars_master.lesson_status,contents.title
            from `contents`
            join scormvars_master on contents.id = scormvars_master.content_id
                        and scormvars_master.course_id = ?
            group by scormvars_master.course_id ,scormvars_master.content_id,scormvars_master.lesson_status,contents.title
        ) i
        left join
        (
            select count(content_id) passess,scormvars_master.id id
            from `contents` join scormvars_master on contents.id = scormvars_master.content_id
                        and scormvars_master.course_id = ?
            where lesson_status = 'completed'
            group by scormvars_master.course_id ,scormvars_master.content_id,scormvars_master.lesson_status,contents.title
        ) other on other.id = i.id";
        $scorms = DB::select($sql,[$course_id,$course_id ]);

        $paginator = Paginator::GetPaginator($scorms);
        $scorms = $paginator->items();
        $count = $paginator->total();

        $sql2 = "SELECT * FROM courses where id = ? " ;
        $course = DB::select($sql2,[$course_id ]);
            // dd($course);
        // $scorms = $scorms->get();
        // dd($scorms);
        return view('training.reports.courses.course_report',compact('scorms','course','course_id','paginator','count'));
    }

    public function progressDetails()
    {

        session()->put('active_sidebar_route_name',-1);

        $user_id = request()->user_id;
        $course_id = request()->course_id;
        $back_page = request()->back_page??'users_courses';
        $preview_gate_allows = Gate::allows('preview-gate');

        // clear session => (Sidebar active color)
        session()->put('Infrastructure_id', -1);

        $course_registration = CourseRegistration::where('course_id', $course_id)
        ->where('user_id', $user_id)
        ->select('id', 'role_id')
        ->first();

        $role_id = (!$preview_gate_allows) ? $course_registration->role_id : -1;
       // Get Course With Contents

        $course = $this->getCourseWithContents($course_id, $role_id);
        // $course = $this->getCourseWithContents($course_id, $role_id);
        // validate if course exists or not
        if(!$course){
            abort(404);
        }// end if

        // Get total rate for course
        $total_rate = app('App\Http\Controllers\Front\CourseController')->getTotalRateForCourse($course->id);

        // Get User Course Activities
        $activities = app('App\Http\Controllers\Front\CourseController')->getUserCourseActivities($course->id, $user_id);
        $user   = User::where('id',$user_id)->first();
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

    public function getCourseWithContents($course_id, $role_id){

        $course = Course::where('id', $course_id)->where('branch_id',getCurrentUserBranchData()->branch_id);

        // if(!Gate::allows('preview-gate')){

        //     $course = $course->whereHas('users', function ($q){
        //         $q->where('users.id', \auth()->id());
        //     })->with(['users' => function($query){
        //         $query->where('user_id', \auth()->id());
        //     }, 'course_rate' => function($query){
        //         return $query->where('user_id', \auth()->id());
        //     }]);
        // }

        $course = $course->with(['uploads' => function($query){
            return $query->where(function ($q){
                $q->where('post_type','intro_video')->orWhere('post_type', 'image');
            });
        }, 'contents' => function($query) use($role_id){
            $query->where('parent_id',null)->with(['gift','details',
                'contents' => function($q){
                    return $q->orderBy('order');
                },
                'contents.details','contents.user_contents' => function($q){
                    return $q->where('user_id', \auth()->id());
                }])
            ->orderBy('order');

            if($role_id != -1){
                $role = \App\Models\Training\Role::where('id',$role_id)->first();
                if ($role->role_type_id == 512){
                    $query->where('hide_from_trainees', 0);
                }
            }
        }])->first();

        return $course;
    } // end function






}
