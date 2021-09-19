<?php

namespace App\Http\Controllers\Front\Education;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training\Course;

class ScoutController extends Controller
{
    public function GetQuery(){
        if(!request()->has('q') || empty(request()->q)){
            return null;
        }
        $courses = Course::search(request()->q)->get();
        return json_encode($courses);

        $session_array = [];
        foreach($courses as $session){
            array_push($session_array, [
                'id'=>$session->id,
            ]);
        }
        return json_encode($session_array);
    }
}
