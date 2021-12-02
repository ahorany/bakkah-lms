<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\User;
use App\Models\Training\Course;
use App\Constant;
use App\Models\Training\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function __construct()
    {
        Active::$namespace  = 'training';
        Active::$folder     = 'reports';
    }

    public function usersReportOverview()
    {
        $user_id = request()->id;
        $learners_no         = DB::table('role_user')->where('role_id',3)->where('user_id',$user_id)->count();
        $complete_courses_no = DB::table('courses_registration')->where('user_id',$user_id)->where('progress',100)->count();
        $courses_in_progress = DB::table('courses_registration')->where('progress','<',100)->where('user_id',$user_id)->count();
        $courses_not_started = DB::table('courses_registration')->where('progress',0)->where('user_id',$user_id)->count();
        $overview = 1;
        return view('training.reports.users.user_report',compact('user_id','learners_no','complete_courses_no','courses_in_progress','courses_not_started','overview'));

    }


    public function usersReportCourse()
    {
        $user_id = request()->id;
        $courses  = DB::table('courses')
                        ->join('courses_registration','courses.id','courses_registration.course_id')
                        ->where('user_id',$user_id)->get();
        // dd($courses);
        return view('training.reports.users.user_report',compact('user_id','courses'));

    }

    public function usersReportTest()
    {
        $user_id = request()->id;
        $tests  = DB::table('contents')
                        ->join('exams','exams.content_id','contents.id')
                        ->join('user_exams','user_exams.exam_id','exams.id')
                        ->join('courses','courses.id','contents.course_id')
                        ->where('user_exams.user_id',$user_id)
                        ->select('user_exams.id','contents.title as content_title','courses.title as course_title','user_exams.time','exams.exam_mark','exams.pass_mark','user_exams.mark')
                        ->orderBy('user_exams.time')
                        ->get();
        //dd($tests);

        return view('training.reports.users.user_report',compact('user_id','tests'));
    }


    public function coursesReportOverview()
    {
        $course_id = request()->id;
        $assigned_learners = DB::table('courses_registration')->where('course_id',$course_id)->count();

        $overview = 1;
        return view('training.reports.courses.course_report',compact('course_id','overview','assigned_learners'));

    }

    public function coursesReportUser()
    {
        $course_id = request()->id;
        $users   = DB::table('courses_registration')
                        ->where('course_id',$course_id)
                        ->join('users','users.id','courses_registration.user_id')
                        ->select('users.id','users.name','courses_registration.progress')
                        ->orderBy('users.id')
                        ->get();
        // dd($users);
        return view('training.reports.courses.course_report',compact('course_id','users'));

    }
    public function coursesReportTest()
    {
        $course_id = request()->id;
        $tests  = DB::table('contents')
                        ->join('exams','exams.content_id','contents.id')
                        ->join('courses','courses.id','contents.course_id')
                        ->where('courses.id',$course_id)
                        ->select('exams.id','contents.title as content_title')
                        ->get();
        return view('training.reports.courses.course_report',compact('course_id','tests'));

    }









}
