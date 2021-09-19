<?php

namespace App\Helpers\Models\Training;

use App\Models\Training\Course;

class CourseHelper {

    public function AllCourses(){
        $all_courses = Course::has('trainingOptions.sessions')
            ->with(['trainingOptions.sessions'=>function($query){
                $query->whereDate('date_from', '>=', now());
                $query->where('session_start_time', '>=', DateTimeNowAddHours());
            }])
            ->where('active',1)
            ->orderBy('title->en')
            ->get();
        return $all_courses;
    }

    public function AllCoursesExternal(){
        $all_courses = Course::has('trainingOptions.sessions')
            ->with(['trainingOptions.sessions'=>function($query){
                $query->whereDate('date_from', '>=', now());
                $query->where('session_start_time', '>=', DateTimeNowAddHours());
            }])
            ->where('show_in_website', 1)
            ->orderBy('order')
            ->get();
        return $all_courses;
    }
}
