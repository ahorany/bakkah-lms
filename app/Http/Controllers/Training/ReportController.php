<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\User;
use App\Models\Training\Course;
use App\Models\Training\Content;

use App\Constant;
use App\Models\Training\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\Training\CourseRegistration;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


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
        $user = User::findOrFail($user_id);

        $learners_no         = DB::table('model_has_roles')->where('role_id',3)->where('model_id',$user_id)->count();
        $complete_courses_no = DB::table('courses_registration')->where('user_id',$user_id)->where('progress',100)->count();
        $courses_in_progress = DB::table('courses_registration')->where('progress','<',100)->where('user_id',$user_id)->count();
        $courses_not_started = DB::table('courses_registration')->where('progress',0)->where('user_id',$user_id)->count();
        $overview = 1;

        return view('training.reports.users.user_report',compact('user_id','learners_no','complete_courses_no',
            'courses_in_progress','courses_not_started','overview', 'user'));
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

    public function usersReportScorm()
    {
        // $contents = DB::table('scormvars_master')->pluck('content_id')->toArray();
        // $contents_unique = array_unique($contents);
        // $scorms = Content::whereIn('id',$contents_unique);

        $user_id = request()->user_id;
        $user = User::find($user_id);

        $scorms = DB::table('scormvars_master')
        ->join('users', function ($join) {
            $join->on('scormvars_master.user_id', '=', 'users.id');
            })
        ->join('contents', function ($join) {
        $join->on('scormvars_master.content_id', '=', 'contents.id');
        })
        ->join('courses', function ($join) {
            $join->on('courses.id', '=', 'contents.course_id');
            })
        ->where('scormvars_master.user_id',$user_id)->select('contents.id','courses.title as crtitle','contents.title as cotitle','scormvars_master.date','scormvars_master.score','scormvars_master.lesson_status')->get();
        // dd($scorms);

        return view('training.reports.users.user_report',compact('user_id', 'scorms','user'));


    }


    public function usersReportCourse()
    {
        $user_id = request()->id;
        $user = User::find($user_id);

        $courses  = DB::table('courses')
        ->join('courses_registration','courses.id','courses_registration.course_id')
        ->where('user_id',$user_id)->get();
        // dd($courses);
        return view('training.reports.users.user_report',compact('user_id', 'courses', 'user'));

    }

    public function usersReportTest()
    {
        $user_id = request()->id;
        $user = User::find($user_id);

        $tests  = DB::table('contents')
        ->join('exams','exams.content_id','contents.id')
        ->join('user_exams','user_exams.exam_id','exams.id')
        ->join('courses','courses.id','contents.course_id')
        ->where('user_exams.user_id',$user_id)
        ->select('user_exams.id', 'contents.title as content_title', 'courses.title as course_title', 'user_exams.time'
        , 'exams.exam_mark', 'exams.pass_mark', 'user_exams.mark as exam_trainee_mark', 'user_exams.status')
        ->orderBy('user_exams.time')
        ->get();
        //dd($tests);
        return view('training.reports.users.user_report',compact('user_id', 'tests', 'user'));
    }


    public function coursesReportOverview()
    {
        $course_id = request()->id;
        $course = Course::find($course_id);
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
        $course = Course::find($course_id);
        $branch_id = getCurrentUserBranchData()->branch_id;
        $select = 'select users.id,users.name,courses_registration.progress,roles.role_type_id,sessions.date_from,sessions.date_to';

        $from = ' from courses_registration
                    join roles on roles.id = courses_registration.role_id
                                        and roles.deleted_at is null
                                        and roles.branch_id = '.$branch_id.'
                    join users on users.id = courses_registration.user_id
                    join courses on courses.id = courses_registration.course_id
                    left join sessions on sessions.course_id = courses.id
                where courses_registration.course_id = '.$course_id.'
                order by users.id
                ';
        $sql = $select.$from;
        $users = DB::select($sql);

        if(isset(request()->export))
        {
            return Excel::download(new UsersExport($from), 'NotCompleted.xlsx');
        }

        return view('training.reports.courses.course_report',compact('course_id', 'users', 'course'));

    }

    public function coursesReportTest()
    {
        $course_id = request()->id;
        // dd($course_id);
        $course = Course::find($course_id);
        $sql = "select exams.id , contents.title as content_title
                from contents
                    join exams on exams.content_id = contents.id
                    join courses on courses.id = contents.course_id
                where courses.id = ".$course_id." ";

        $tests = DB::select($sql);

        return view('training.reports.courses.course_report',compact('course_id', 'tests', 'course'));

    }



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










}
