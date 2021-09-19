<?php

namespace App\Http\Controllers;

use App\Mail\InterestEmail;
use App\UserFromExcel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserFromExcelController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function send()
    {
        $UserFromExcels = UserFromExcel::all();
        foreach($UserFromExcels as $UserFromExcel){
            Mail::to($UserFromExcel->email)->send(new InterestEmail($UserFromExcel));
        }
        // $job = (new InterestEmailJob(1))
        //             ->delay(\Carbon\Carbon::now()->addSeconds(5));
        // dispatch($job);
        dd("Done");
    }
}
