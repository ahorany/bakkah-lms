<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\CourseRequest;
use App\Http\Requests\Training\GroupRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Course;
use App\Constant;
use App\Models\Training\Group;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Str;
use DB;

class GroupController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'groups';
    }

    public function index(){
        $post_type = 'groups';
        $trash = GetTrash();
        $groups = Group::query();
        $count = $groups->count();
        $groups = $groups->page();

        $assigned_users     = DB::table('user_groups')->count(DB::raw('DISTINCT user_id'));
        $assigned_courses   = DB::table('course_groups')->count(DB::raw('DISTINCT course_id'));
        $completed_courses  = DB::table('user_groups')
        ->join('course_groups', function ($join) {
            $join->on('course_groups.group_id', '=', 'user_groups.group_id')
                 ->where('user_groups.role_id',3);
        })
        ->join('courses_registration as cr1', function ($join) {
            $join->on('cr1.course_id', '=', 'course_groups.course_id')
                 ->where('cr1.progress',100);
        })
        ->join('courses_registration as cr2','cr2.user_id','user_groups.user_id')
        ->count('cr1.course_id');

        // dd($completed_courses);
        return Active::Index(compact('groups', 'count', 'post_type', 'trash','assigned_users','assigned_courses','completed_courses'));
    }

    public function create(){
        return Active::Create();
    }

    public function store(GroupRequest $request){

        $validated = $request->validated();
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;

        $group= Group::create($validated);
        \App\Models\SEO\Seo::seo($group);
        return Active::Inserted($group->name);
    }

    public function edit(Group $group){
        return Active::Edit(['eloquent'=>$group]);
    }

    public function update(GroupRequest $request,Group $group){
        $validated = $request->validated();
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;
        Group::find($group->id)->update($validated);
        Group::UploadFile($group, ['method'=>'update']);
        \App\Models\SEO\Seo::seo($group);
        return Active::Updated($group->name);
    }

    public function destroy(Group $group){
        Group::where('id', $group->id)->SoftTrash();
        return Active::Deleted($group->name);
    }

    public function restore($group){
        Group::where('id', $group)->RestoreFromTrash();
        $group = Group::where('id', $group)->first();
        return Active::Restored($group->name);
    }

}
