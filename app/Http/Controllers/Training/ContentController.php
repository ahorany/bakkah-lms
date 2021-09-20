<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training\Content;
use App\Models\Training\ContentDetails;
use App\Models\Training\Course;
use Illuminate\Support\Facades\Request;

class ContentController extends Controller
{
    public function contents()
    {
        $course_id = request()->course_id;
        $course = Course::with(['upload', 'user'])->where('id',$course_id)->first();
        $contents = Content::where('course_id',$course_id)->get();
        return view('training.courses.contents.index', compact('course', 'contents'));
    }

    public function showModal()
    {
        $course_id = request()->course_id;
        return view('training.courses.contents.section', compact('course_id'));
    }

    public function showChildModal()
    {
        $page = request()->type;
        $course_id = request()->course_id;
        return view('training.courses.contents.'.$page, compact('course_id'));

    }


    public function add_section()
    {

        $course_id = request()->course_id;

        return \request()->title;
//        return request()->all();
//        $imageName = time().'.'.$request->name->getClientOriginalExtension();
//        $request->image->move(public_path('images'), $imageName);

        $content = Content::create([
                     'title'      => request()->title,
                     'course_id'  =>request()->course_id,
                     'post_type'  => request()->type,
                     'parent_id'  => request()->content_id,
          ]);

        if (request()->type == 'section'){
            ContentDetails::create([
                'excerpt'    =>  request()->excerpt,
                'content_id' => $content->id,
            ]);
        }

        $course = Course::with(['upload', 'user'])->where('id',$course_id)->first();
        $contents = Content::where('course_id',$course_id)->get();
        return $contents;
    }
}
