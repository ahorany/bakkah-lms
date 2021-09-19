<?php

namespace App\Http\Controllers\Training;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training\Content;
use App\Models\Training\Course;
class ContentController extends Controller
{
    public function contents()
    {
        // return request()->all();
        $course_id = request()->course_id;

        $course = Course::with(['upload', 'user'])->where('id',$course_id)->first();
        $contents = Content::where('course_id',$course_id)->get();
        // dd($contents);
        // return $courses;
        // $contents = Content::get();
        return view('training.courses.contents.index', compact('course', 'contents'));

    }

    public function showModal()
    {
        $course_id = request()->course_id;
        return view('training.courses.contents.section', compact('course_id'));
    }

    public function add_section()
    {
        $course_id = request()->course_id;

        $content = new Content;
        // return request()->excerpt;
        $content->title      = request()->title;
        $content->excerpt    = request()->excerpt;
        $content->course_id  = request()->course_id;

        $content->save();
// 
        // $course = Course::with(['upload', 'user'])->where('id',$course_id)->first();
        // $contents = Content::where('course_id',$course_id)->get();
        
        // return view('training.courses.contents.index', compact('course','contents'));
    }



}
