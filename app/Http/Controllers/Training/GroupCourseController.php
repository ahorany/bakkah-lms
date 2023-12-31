<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Group;
use App\Models\Training\GroupCourse;
use App\Models\Training\GroupUser;


class GroupCourseController extends Controller
{

    public function group_courses ()
    {
        $group_id = request()->group_id;
        $group = Group::with(['upload', 'courses'])->where('branch_id',getCurrentUserBranchData()->branch_id)
                                                    ->where('id',$group_id)->first();
        if (!$group) abort(404);
        return view('training.groups.courses.index', compact('group'));
    }

    public function delete_course_group(){
        $course =  Course::whereId(\request()->course_id)->where('branch_id',getCurrentUserBranchData()->branch_id)->first();
        if (!$course) abort(404);

        $group =  Group::whereId(\request()->group_id)->where('branch_id',getCurrentUserBranchData()->branch_id)->first();
        if (!$group) abort(404);

        $this->delete_group_users_from_course_registration(\request()->group_id,\request()->course_id);

        GroupCourse::where('course_id',$course->id)->where('group_id',$group->id)->delete();
        return response()->json(['status' => 'success']);
    }

    public function search_course_group(){
       $courses = Course::where('branch_id',getCurrentUserBranchData()->branch_id);

       $lock = true;
       if( is_null(request()->name)){
           $courses = [];
           $lock = false;
       }

        if(!is_null(request()->name)) {
            $courses = $courses->where(function($query){
                $query->where('title', 'like', '%'.request()->name.'%');
            });
        }


        if($lock){
            $courses = $courses->get();
        }
        return response()->json([ 'status' => 'success' ,'courses' => $courses]);
    }

    private function add_group_users_in_course_registration($group_id , $course_id,$group_expire_date){
         $group_users = GroupUser::where('group_id',$group_id)->get();
         foreach ($group_users as $group_user ){
             $course_registration = CourseRegistration::updateOrCreate([
                 'user_id' => $group_user->user_id,
                 'course_id' => $course_id,
                 'role_id' => $group_user->role_id,
             ],[
                 'user_id' => $group_user->user_id,
                 'course_id' => $course_id,
                 'role_id' => $group_user->role_id,
                 'expire_date' => $group_expire_date,
             ]);
         }
    }

    private function delete_group_users_from_course_registration($group_id,$course_id)
    {
        $group_users = GroupUser::where('group_id',$group_id)->get();

        foreach ($group_users as $group_user ){
             CourseRegistration::where('user_id',$group_user->user_id)
                 ->where('course_id',$course_id)
                 ->where('role_id' , $group_user->role_id)->delete();
        }

    }

        public function add_course_group(){
        $group = Group::whereId(\request()->group_id)->where('branch_id',getCurrentUserBranchData()->branch_id)->first();

        if(!$group){
            return response()->json([ 'status' => 'fail']);
        }

        foreach (\request()->courses as $key =>  $value){
            if ($value == true){
                GroupCourse::updateOrcreate([
                    'course_id' => $key,
                    'group_id' => $group->id,
                ],
                [
                    'course_id' => $key,
                    'group_id' => $group->id,
                ]);

                $this->add_group_users_in_course_registration($group->id , $key,$group->expire_date);

            }else if ($value == false){
                $this->delete_group_users_from_course_registration($group->id,$key);
                GroupCourse::where('course_id',$key)->where('group_id',$group->id)->delete();
            }
        }
        $group = Group::with(['courses'])->where('id',$group->id)->first();

        return response()->json([ 'status' => 'success' ,'group' => $group]);
    }
}
