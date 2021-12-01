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
        Active::$namespace = 'training';
        Active::$folder = 'reports';
    }

    public function user_report()
    {

        $user_id = request()->id;
        $learners_no         = DB::table('role_user')->where('role_id',3)->where('user_id',$user_id)->count();
        $complete_courses_no = DB::table('courses_registration')->where('user_id',$user_id)->where('progress',100)->count();
        $courses_in_progress = DB::table('courses_registration')->where('progress','<',100)->where('user_id',$user_id)->count();
        $courses_not_started = DB::table('courses_registration')->where('progress',0)->where('user_id',$user_id)->count();
        $overview = 1;
        return view('training.reports.user_report',compact('user_id','learners_no','complete_courses_no','courses_in_progress','courses_not_started','overview'));

    }


    public function courseReport()
    {
        $user_id = request()->id;
        $courses         = DB::table('courses')
                                ->join('courses_registration','courses.id','courses_registration.course_id')
                                ->where('user_id',$user_id)->get();

        return view('training.reports.user_report',compact('user_id','courses'));

    }






}
