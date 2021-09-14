<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Active;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RobotController extends Controller
{
    public function __construct(){
        Active::$folder = 'robot';
    }

    public function index()
    {
        $folder = 'robot';
        $post_type  = 'robot';
        Active::Link($post_type);

        $path = (env('NODE_ENV')=='production') ? 'robots.txt' : public_path('robots.txt');
        $data = file_get_contents($path);
        return Active::Create(compact('folder','post_type','data'));
    }

    public function store(Request $request){

        $post_type  = 'robot_'.time().'_'.date('Y_m_d_h_i_m').'_'.auth()->user()->id;

        $path = public_path('robots/'.$post_type.'.txt');

        $robot_path = (env('NODE_ENV')=='production') ? 'robots.txt' : public_path('robots.txt');

        $robot_data = file_get_contents($robot_path) ;
        file_put_contents($robot_path,$request->robot_txt) ;

        $handel =  fopen($path,'a+');
        fwrite($handel,$robot_data);
        fclose($handel);

        Active::Flash($post_type, __('flash.updated'), 'success');
        return back();
    }
}
