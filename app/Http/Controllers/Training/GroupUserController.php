<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Group;
use App\Models\Training\GroupCourse;
use App\Models\Training\GroupUser;
use App\User;


class GroupUserController extends Controller
{


    public function group_users()
    {
        $group_id = request()->group_id;
        $group = Group::with(['upload', 'users'])->where('id',$group_id)->first();
        // return $group;
        return view('training.groups.users.index', compact('group'));
    }

    public function delete_user_group(){
        $user_id = \request()->user_id;
        $group_id = \request()->group_id;
        $user =  User::findOrFail($user_id);
        $group =  Group::findOrFail($group_id);
        $group_user = GroupUser::where('group_id',$group_id)->where('user_id',$user_id)->first();
        if(!$group_user){
            abort(404);
        }
        $this->delete_user_from_course_registration($group->id,$user_id,$group_user->role_id);

        GroupUser::where('user_id',$user->id)->where('group_id',$group->id)->delete();
        return response()->json(['status' => 'success']);
    }

    public function search_user_group(){
        $user_type = 2;
        if(\request()->user_type == 'trainee'){
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



        if($lock){
            if($user_type == 2 ){
                $users->whereHas('roles' , function($q) use($user_type){
                    $q->where('role_id','!=',3);
                });
            }


//            dd($users->toSql());
            $users = $users->get();
        }

        return response()->json([ 'status' => 'success' ,'users' => $users]);
    }


    private function add_user_with_all_group_courses_in_course_registration($user_id,$role_id,$group_id,$group_expire_date){
         $group_courses = GroupCourse::where('group_id',$group_id)->get();
         foreach ($group_courses as $group_course){
             CourseRegistration::updateOrCreate([
                 'user_id' => $user_id,
                 'course_id' => $group_course->course_id,
                 'role_id' => $role_id,
             ],[
                 'user_id' => $user_id,
                 'course_id' => $group_course->course_id,
                 'role_id' => $role_id,
                 'expire_date' => $group_expire_date,
             ]);
         }

    }

    private function delete_user_from_course_registration($group_id,$user_id,$role_id)
    {
        $group_courses = GroupCourse::where('group_id',$group_id)->get();

        foreach ($group_courses as $group_course ){
            CourseRegistration::where('user_id',$user_id)
                ->where('course_id',$group_course->course_id)
                ->where('role_id' , $role_id)->delete();
        }

    }


    public function add_users_group(){
        $group = Group::find(\request()->group_id);

        if(!$group){
            return response()->json([ 'status' => 'fail']);
        }

        if(request()->user_type == 'instructor'){
            $type_id = 2;
        }else{
            $type_id = 3;
        }

        foreach (\request()->users as $key =>  $value){
            if ($value == true){
                GroupUser::updateOrcreate([
                    'user_id' => $key,
                    'group_id' => $group->id
                ],
                [
                    'user_id' => $key,
                    'group_id' => $group->id,
                    'role_id' => $type_id,
                ]);

                $this->add_user_with_all_group_courses_in_course_registration($key,$type_id,$group->id,$group->expire_date);
            }else if ($value == false){
                $this->delete_user_from_course_registration($group->id,$key,$type_id);
                GroupUser::where('user_id',$key)->where('group_id',$group->id)->delete();
            }
        }
        $group = Group::with(['users'])->where('id',$group->id)->first();

        return response()->json([ 'status' => 'success' ,'group' => $group]);
    }
}
