<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\User;
use App\Models\Training\Course;
use App\Constant;
use App\Models\Training\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'reports';
    }

    public function index(){

        $users = User::join('role_user','users.id','=','role_user.user_id')
                        ->join('roles','roles.id','role_user.role_id');

        // if(isset(request()->title) && !is_null(request()->title))
        // {
        //     $projects = $projects->where('title', 'like', '%'.request()->title.'%');
        // }
        $trash = GetTrash();
        $count = $users->count();
        $post_type = GetPostType();
        $users = $users->select('users.*', 'roles.name as role_name')->page(null, 'users.');
        // $projects = $users->select('users.*', 'roles.name')->get();
        $learners_no  = DB::table('role_user')->where('role_id',3)->count();
        $complete_courses_no = DB::table('courses_registration')->where('progress',100)->count();
        $courses_in_progress = DB::table('courses_registration')->where('progress','<',100)->count();
        return Active::Index(compact( 'users', 'post_type', 'trash','count','learners_no','complete_courses_no','courses_in_progress'));

    }






}
