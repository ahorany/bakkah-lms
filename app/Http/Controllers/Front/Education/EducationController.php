<?php

namespace App\Http\Controllers\Front\Education;

use App\Http\Controllers\Controller;

class EducationController extends Controller
{
    public function __construct(){
        $this->path = FRONT.'.education';
    }

    public function index(){

        dd('education.index');
    }

}
