<?php

namespace App\Http\Controllers\Training;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use App\Jobs\InterestEmailJob;
use App\Models\Training\CourseInterest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'interests';
    }

    public function index(){
        $post_type = GetPostType('interest');
        $interests = CourseInterest::whereNotNull('id');
        $trash = GetTrash();
        $count = $interests->count();
        $interests = $interests->page();

        return Active::Index(compact('interests', 'trash', 'count', 'post_type'));
    }

    public function destroy(CourseInterest $interest){
        CourseInterest::where('id', $interest->id)->SoftTrash();
    }

    public function restore($interest){
        CourseInterest::where('id', $interest)->RestoreFromTrash();
    }

    public function target($course_id){
        $courseInterests = CourseInterest::where('course_id', $course_id)->where('id', 166)->get();
        $args = [];
        $minute = 1;
        foreach($courseInterests as $courseInterest){
            if(!in_array($courseInterest->userId->email, $args)){

                $job = (new InterestEmailJob($courseInterest->userId))
                        ->delay(\Carbon\Carbon::now()->addSeconds($minute));
                dispatch($job);
                // dump($courseInterest->userId->email);
                array_push($args, $courseInterest->userId->email);

                $minute++;
            }
        }
    }
}
