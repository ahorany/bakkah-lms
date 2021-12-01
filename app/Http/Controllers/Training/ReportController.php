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

        // dd(request()->all());
        $users = User::join('role_user','users.id','=','role_user.user_id')
                        ->join('roles','roles.id','role_user.role_id');

        if (!is_null(request()->user_search)) {
            $users = $users->where(function ($query) {
                $query->where('users.name', 'like', '%' . request()->user_search . '%')
                    ->orWhere('users.email', 'like', '%' . request()->user_search . '%')
                    ->orWhere('users.mobile', 'like', '%' . request()->user_search . '%')
                    ->orWhere('users.job_title', 'like', '%' . request()->user_search . '%')
                    ->orWhere('users.company', 'like', '%' . request()->user_search . '%');
            });
        }

        $trash = GetTrash();
        $count = $users->count();
        $post_type = GetPostType();

        $users = $users->select('users.*', 'roles.name as role_name')->page(null, 'users.');

        $learners_no  = DB::table('role_user')->where('role_id',3)->count();
        $complete_courses_no = DB::table('courses_registration')->where('progress',100)->count();
        $courses_in_progress = DB::table('courses_registration')->where('progress','<',100)->count();
        $all_users =  User::all();
        return Active::Index(compact( 'users', 'post_type', 'trash','count','learners_no','complete_courses_no','courses_in_progress','all_users'));

    }






}
