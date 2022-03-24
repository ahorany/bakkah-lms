<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Mail\TraineeMail;
use App\Models\Training\Role;
use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\Models\Training\Session;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class CourseUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:course.users.list');
    }

    public function course_users()
    {
        $course_id = request()->course_id;
        $bindings = [$course_id];
        $sql = "SELECT courses.id,courses.title,courses.training_option_id FROM `courses` WHERE courses.id = ?";
        $course = DB::select($sql,$bindings);


        $sql = "SELECT courses_registration.id,sessions.date_from,sessions.date_to,courses_registration.session_id,courses_registration.role_id,courses_registration.progress,courses_registration.user_id,
                courses_registration.expire_date,courses_registration.paid_status,users.name as user_name,users.email FROM `courses_registration`
                 INNER  JOIN users ON users.id = courses_registration.user_id AND users.deleted_at IS NULL
                 LEFT JOIN sessions ON sessions.id =  courses_registration.session_id
                 WHERE courses_registration.course_id = ?";

        $sessions = Session::select('id','date_from','date_to')->where('course_id',$course_id)->get();


        if (\request()->has('user_search') && !is_null(request()->user_search)) {
            $user_search = request()->user_search;
            $bindings[] = '%'.$user_search.'%';
            $bindings[] = '%'.$user_search.'%';
            $sql .= " AND (users.name LIKE ? OR users.email LIKE ?)";
        }


        $course_users = DB::select($sql,$bindings);
        return view('training.courses.users.index', compact('course','course_users','sessions'));
    }




    public function search_user_course(){
        $lang = app()->getLocale();
        $name = '%'.request()->name.'%';
        $email = '%'.request()->email.'%';
        $course_id = request()->course_id;
        $bindings = [];

        $sql = "SELECT courses_registration.id,courses_registration.session_id,courses_registration.role_id,courses_registration.progress,courses_registration.user_id,
                courses_registration.expire_date,courses_registration.paid_status,users.id as user_id,users.name as user_name,users.email
                FROM users
                LEFT JOIN courses_registration ON users.id = courses_registration.user_id
                AND courses_registration.course_id = $course_id
               ";

        if (\request()->name && request()->email){
            $bindings = [$name,$email];
            $sql .= " WHERE JSON_VALUE(users.name, '$.$lang') LIKE ?  OR users.email LIKE ?";
        }

        if (\request()->name){
            $bindings = [$name];
            $sql .= " WHERE JSON_VALUE(users.name, '$.$lang') LIKE ?";
        }

        if (request()->email){
            $bindings = [$email];
            $sql .= " WHERE users.email LIKE ?";
        }

        $users = DB::select($sql,$bindings);
        return response()->json([ 'status' => 'success' ,'users' => $users]);
    }



    public function add_users_course(){
        $course = Course::find(\request()->course_id);
        if(!$course){
            return response()->json([ 'status' => 'fail']);
        }


        foreach (request()->delete_users as  $user){
            CourseRegistration::where('course_id',$course->id)->where('user_id',$user['user_id'])->delete();
        }


        foreach (request()->users as  $user){
                CourseRegistration::updateOrcreate(
                    [
                        'user_id'        => $user['user_id'],
                        'course_id'      => $course->id
                    ],
                    [
                        'user_id'        => $user['user_id'],
                        'course_id'      => $course->id,
                        'expire_date'    => $user['expire_date'],
                        'role_id'        => $user['role_id'],
                        'paid_status'    => $user['paid_status'],
                        'session_id'     => $user['session_id'],
                    ]
                );
                if($user['role_id'] == 3){
                    $user = User::where('id',$user['user_id'])->first();
                    Mail::to($user->email)->send(new TraineeMail($user->id, $course->id));
                }
        }
        return response()->json([ 'status' => 'success']);
    }



    public function delete_user_course(){
        $user_id = \request()->user_id;
        $course_id = \request()->course_id;
        $user =  User::findOrFail($user_id);
        $course =  Course::findOrFail($course_id);
        CourseRegistration::where('user_id',$user->id)->where('course_id',$course->id)->delete();
        return response()->json(['status' => 'success']);
    }


} // End Class
