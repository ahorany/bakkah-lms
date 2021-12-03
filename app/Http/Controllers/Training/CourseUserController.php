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
        if(checkUserIsTrainee()){
            abort(404);
        }

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
        return view('training.courses.users.index', compact('course'));
    }

    public function delete_user_course(){
        if(checkUserIsTrainee()){
            abort(404);
        }
        $user_id = \request()->user_id;
        $course_id = \request()->course_id;
        $user =  User::findOrFail($user_id);
        $course =  Course::findOrFail($course_id);
        CourseRegistration::where('user_id',$user->id)->where('course_id',$course->id)->delete();
        return response()->json(['status' => 'success']);
    }

    public function search_user_course(){
        if(checkUserIsTrainee()){
            abort(404);
        }
        $user_type = 2;
        if(\request()->type_user == 'trainee'){
            $user_type = 3;
        }
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

//        $users->with(['roles' => function($q) use($user_type){
//            return  $q->where('role_id',$user_type);
//        }]);

        if($lock){
            if($user_type == 2 ){
                $users->whereHas('roles' , function($q) use($user_type){
                    $q->where('role_id','!=',3);
                });
            }
            $users = $users->get();
        }

        return response()->json([ 'status' => 'success' ,'users' => $users]);
    }

    public function add_users_course(){
        if(checkUserIsTrainee()){
            abort(404);
        }
//        return \request();
        $course = Course::find(\request()->course_id);

        if(!$course){
            return response()->json([ 'status' => 'fail']);
        }

//       $type_id =  (Role::where("name", 'like', '%'.request()->type.'%')->first())->id;
        if(request()->type == 'instructor'){
            $type_id = 2;
        }else{
            $type_id = 3;
        }

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
        $course = Course::with(['users.roles'])->where('id',$course->id)->first();

        return response()->json([ 'status' => 'success' ,'course' => $course]);


    }
}
