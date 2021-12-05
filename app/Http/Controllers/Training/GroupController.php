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

        return Active::Index(compact('groups', 'count', 'post_type', 'trash','assigned_users','assigned_courses'));
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
