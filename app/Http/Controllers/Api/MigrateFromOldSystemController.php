<?php

namespace App\Http\Controllers\Api;

use App\Models\Training\Course;
use App\Models\Training\CourseRegistration;
use App\Models\Training\Session;
use App\Models\Training\UserBranch;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Mail\UserMail;
use Illuminate\Support\Facades\Mail;

class MigrateFromOldSystemController
{
    public function update_progress_content(){

        $users = User::where('to_migrate', 1)->get();
        dd($users);
        foreach($users as $user){

        }
    }
}
