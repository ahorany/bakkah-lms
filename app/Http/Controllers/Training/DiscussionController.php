<?php

namespace App\Http\Controllers\Training;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Http\Requests\Training\DiscussionRequest;
use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Discussion;
use App\Models\Training\Message;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'discussions';
    }
    public function index(){
        $course_id = \request()->course_id;
        $trash = GetTrash();
        $course = Course::select('id','title')->whereid($course_id)->first();
        if (!$course){
            abort(404);
        }

        $courseRegistration = CourseRegistration::where('user_id',auth()->id())->where('course_id',$course_id)->first();
        if (!$courseRegistration){
            abort(404);
        }

        $discussions = Discussion::with(['message' => function($q) use($course_id){
            $q->where('course_id',$course_id)->with(['user']);
        }]);

        $count = $discussions->count();
        $discussions = $discussions->page();


        return Active::Index(compact('count','discussions','course','trash'));

    }// end method

    public function create(){
        $course_id = \request()->course_id;
        $course = Course::select('id','title')->whereid($course_id)->first();
        if (!$course){
            abort(404);
        }

        $courseRegistration = CourseRegistration::where('user_id',auth()->id())->where('course_id',$course_id)->first();
        if (!$courseRegistration){
            abort(404);
        }

        return Active::Create([
            'course' => $course,
        ]);
    }


    public function store(DiscussionRequest $request){
        $courseRegistration = CourseRegistration::where('user_id',auth()->id())->where('course_id',$request->course_id)->first();
        if (!$courseRegistration){
            abort(404);
        }

        $message = Message::create([
            'title'       => $request->title,
            'course_id'   => $request->course_id,
            'description' => $request->description,
            'type'        => 'discussion',
            'user_id'     => auth()->id(),
        ]);

          Discussion::create([
              'message_id' => $message->id,
              'start_date' => $request->start_date,
              'end_date'   => $request->end_date,
              'course_id'   => $request->course_id,
          ]);
        return Active::Inserted($message->title,[
            'course_id' => $request->course_id,
        ]);
    }

    public function edit($discussion_id){
        $course_id = \request()->course_id;
        $discussion = Discussion::whereId($discussion_id)->where('course_id',$course_id)
            ->whereHas('message',function ($q) use ($course_id){
            $q->where('user_id',auth()->id())->where('course_id',$course_id);
        })->first();
        if (!$discussion){
            abort(404);
        }


        $courseRegistration = CourseRegistration::where('user_id',auth()->id())->where('course_id',$course_id)->first();
        if (!$courseRegistration){
            abort(404);
        }

        $discussion = Discussion::whereId($discussion_id)->with(['message' => function($q) use($course_id){
            $q->where('course_id',$course_id)->with(['user']);
        }])->first();

        return Active::Edit(['eloquent'=>$discussion]);
    }


    public function update(DiscussionRequest $request,$discussion_id){
        $course_id = \request()->course_id;
        $discussion = Discussion::whereId($discussion_id)->where('course_id',$course_id)
            ->whereHas('message',function ($q) use ($course_id){
                $q->where('user_id',auth()->id())->where('course_id',$course_id);
            })->first();
        if (!$discussion){
            abort(404);
        }

        $courseRegistration = CourseRegistration::where('user_id',auth()->id())->where('course_id',$request->course_id)->first();
        if (!$courseRegistration){
            abort(404);
        }

        $message = Message::whereId($discussion->message_id)->where('course_id',$request->course_id)->update([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        $discussion = Discussion::whereId($discussion->id)->where('course_id',$request->course_id)->update([
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);


        return Active::Updated("Successfully Updated !");
    }





    public function destroy($discussion_id){
        $course_id = \request()->course_id;
        $discussion = Discussion::whereId($discussion_id)->where('course_id',$course_id)
            ->whereHas('message',function ($q) use ($course_id){
                $q->where('user_id',auth()->id())->where('course_id',$course_id);
            })->first();
        if (!$discussion){
            abort(404);
        }

        $courseRegistration = CourseRegistration::where('user_id',auth()->id())->where('course_id',$course_id)->first();
        if (!$courseRegistration){
            abort(404);
        }

        Discussion::whereId($discussion->id)->where('course_id',request()->course_id)->softTrash();
        return Active::Deleted("Successfully Deleted !");
    }


    public function restore($discussion){
        $course_id = \request()->course_id;
        $discussion = Discussion::whereId($discussion)->where('course_id',$course_id)
            ->whereHas('message',function ($q) use ($course_id){
                $q->where('user_id',auth()->id())->where('course_id',$course_id);
            })->withTrashed()->first();
        if (!$discussion){
            abort(404);
        }

        $courseRegistration = CourseRegistration::where('user_id',auth()->id())->where('course_id',$course_id)->first();
        if (!$courseRegistration){
            abort(404);
        }

        Discussion::whereId($discussion->id)->where('course_id',request()->course_id)->RestoreFromTrash();
        return Active::Restored("Successfully Restored!");
    }


}
