<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ZoomApiHelper;
use App\Models\Training\CourseRegistration;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ZoomApiController
{

    public function index(){
        return ZoomApiHelper::createZoomMeeting();
    }


}
