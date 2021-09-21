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
//        $contents = Content::where('course_id',$course_id)->with('details')->latest()->get();
        $contents = Content::where('course_id',$course_id)->whereNull('parent_id')->with(['contents','details'])->latest()->get();

        return view('training.courses.contents.index', compact('course', 'contents'));
    }

    public function delete_content()
    {
        $content_id = request()->content_id;
         Content::where('id',$content_id)->delete();
        return response()->json([ 'status' => 'success']);
    }



    public function add_section()
    {

        $course_id = request()->course_id;


        if (request()->type == 'section'){

            $content = Content::create([
                'title'      => request()->title,
                'course_id'  =>request()->course_id,
                'post_type'  => request()->type,
            ]);

            ContentDetails::create([
                'excerpt'    =>  request()->excerpt,
                'content_id' => $content->id,
            ]);
        }else{
            $content = Content::create([
                'title'      => request()->title,
                'course_id'  =>request()->course_id,
                'post_type'  => request()->type,
                'parent_id'  => request()->content_id,
            ]);
        }

        $course = Course::with(['upload', 'user'])->where('id',$course_id)->first();
//        $contents = Content::where('course_id',$course_id)->with('details')->latest()->get();
        $contents = Content::where('course_id',$course_id)->whereNull('parent_id')->with(['contents','details'])->latest()->get();
        return response()->json($contents);
    }


    public function update_content()
    {

        $course_id = request()->course_id;


        $content = Content::where('id',request()->content_id)->update([
                'title'      => request()->title
        ]);

        $course = Course::with(['upload', 'user'])->where('id',$course_id)->first();
//        $contents = Content::where('course_id',$course_id)->with('details')->latest()->get();
        $contents = Content::where('course_id',$course_id)->whereNull('parent_id')->with(['contents','details'])->latest()->get();
        return response()->json($contents);
    }
}
