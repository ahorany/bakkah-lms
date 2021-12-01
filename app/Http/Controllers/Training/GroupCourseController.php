<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Course;
use App\Models\Training\Group;
use App\Models\Training\GroupCourse;


class GroupCourseController extends Controller
{


    public function group_courses ()
    {
        $group_id = request()->group_id;
        $group = Group::with(['upload', 'courses'])->where('id',$group_id)->first();
        return view('training.groups.courses.index', compact('group'));
    }

    public function delete_course_group(){
        $course_id = \request()->course_id;
        $group_id = \request()->group_id;
        $course =  Course::findOrFail($course_id);
        $group =  Group::findOrFail($group_id);
        GroupCourse::where('course_id',$course->id)->where('group_id',$group->id)->delete();
        return response()->json(['status' => 'success']);
    }

    public function search_course_group(){
       $courses = Course::query();

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

    public function add_course_group(){
        $group = Group::find(\request()->group_id);

        if(!$group){
            return response()->json([ 'status' => 'fail']);
        }

        foreach (\request()->courses as $key =>  $value){
            if ($value == true){
                GroupCourse::updateOrcreate([
                    'course_id' => $key,
                    'group_id' => $group->id
                ],
                [
                    'course_id' => $key,
                    'group_id' => $group->id,
                ]);
            }else if ($value == false){
                GroupCourse::where('course_id',$key)->where('group_id',$group->id)->delete();
            }
        }
        $group = Group::with(['courses'])->where('id',$group->id)->first();

        return response()->json([ 'status' => 'success' ,'group' => $group]);


    }
}
