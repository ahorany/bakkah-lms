<?php

namespace App\Http\Controllers\Training;
use App\Http\Controllers\Controller;
use App\Models\Training\Content;
use App\Models\Training\Course;

class RolePathController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:rolePath.list');
    }

    public function rolePath(){
        $course_id = request()->course_id;
        $course = $this->courseContent($course_id);
        return view('training.courses.contents.role_path', compact('course' , 'course_id'));
    }

    public function sendRolePath(){
        $contents = request()->contents;
        $course_id = request()->course_id;
        $course = $this->courseContent($course_id);
        foreach($course->contents as $content){
            $content_course = Content::where('id',$content->id)->first();
            $content_course->update([
                'role_and_path' => 0,
            ]);
        }
        if($contents != null){
            foreach($contents as $content){
                $content_course = Content::where('id',$content)->first();
                $content_course->update([
                    'role_and_path' => 1,
                ]);
            }
        }

        $course = $this->courseContent($course_id);
        return view('training.courses.contents.role_path', compact('course' , 'course_id'));
    }

    private function courseContent($course_id){
        $course = Course::where('id',$course_id)->with(['contents' => function($q) use ($course_id){
            return $q->where('course_id',$course_id)->with(['contents' => function($q){
                $q->orderBy("order");
            }])->orderBy("order");
        }])->first();

        return $course;
    }

}
