<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Training\Answer;
use App\Models\Training\Content;
use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Exam;
use App\Models\Training\Question;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class CourseUserController extends Controller
{

    public function update_user_expire_date(){
        $user_id = \request()->user_id;
        $course_id = \request()->course_id;
        $expire_date = \request()->expire_date;
        $user =  User::findOrFail($user_id);
        $course =  Course::findOrFail($course_id);
        CourseRegistration::where('user_id',$user->id)->where('course_id',$course->id)->update([
            'expire_date' => $expire_date
        ]);
        return response(['status'=>'success']);
    }
    public function course_users()
    {
        $course_id = request()->course_id;
        $course = Course::with(['upload', 'users'])->where('id',$course_id)->first();
        return view('crm.courses.users.index', compact('course'));
    }

    public function delete_user_course(){
        $user_id = \request()->user_id;
        $course_id = \request()->course_id;
        $user =  User::findOrFail($user_id);
        $course =  Course::findOrFail($course_id);
        CourseRegistration::where('user_id',$user->id)->where('course_id',$course->id)->delete();
        return response('status','success');
    }

    public function search_user_course(){
       $users = User::query();

       $lock = true;
       if(is_null(request()->email) && is_null(request()->name)){
           $users = [];
           $lock = false;
       }
        if(!is_null(request()->email)) {
            $users = $users->where(function($query){
                $query->where('email', 'like', '%'.request()->email.'%');
            });
        }

        if(!is_null(request()->name)) {
            $users = $users->where(function($query){
                $query->where('name', 'like', '%'.request()->name.'%');
            });
        }

        if($lock){
            $users = $users->get();
        }
        return response()->json([ 'status' => 'success' ,'users' => $users]);
    }

    public function add_users_course(){
//        return \request();
        $course = Course::find(\request()->course_id);

        if(!$course){
            return response()->json([ 'status' => 'fail']);
        }

       $type_id =  (Role::where("name", 'like', '%'.request()->type.'%')->first())->id;

        foreach (\request()->users as $key =>  $value){
            if ($value == true){
                CourseRegistration::updateOrcreate([
                    'user_id' => $key,
                    'course_id' => $course->id
                ],
                [
                    'user_id' => $key,
                    'course_id' => $course->id,
                    'expire_date' => request()->expire_date,
                    'role_id' => $type_id,
                ]);
            }else if ($value == false){
                CourseRegistration::where('user_id',$key)->where('course_id',$course->id)->delete();
            }
        }
        $course = Course::with(['users'])->where('id',$course->id)->first();

        return response()->json([ 'status' => 'success' ,'course' => $course]);


    }
}
