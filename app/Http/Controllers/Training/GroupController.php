<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\GroupRequest;
use App\Models\Training\Group;
use DB;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.groups.index');

        Active::$namespace = 'training';
        Active::$folder = 'groups';
    }

    public function index(){
        $post_type = 'groups';
        $trash = GetTrash();
        $groups = Group::where('branch_id',getCurrentUserBranchData()->branch_id);
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
        ->count(DB::raw('DISTINCT cr1.id'));

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
        $validated['branch_id'] =getCurrentUserBranchData()->branch_id;

        $group= Group::create($validated);
        return Active::Inserted($group->name);
    }

    public function edit(Group $group){
        if ($group->branch_id != getCurrentUserBranchData()->branch_id){
            abort(404);
        }
        return Active::Edit(['eloquent'=>$group]);
    }

    public function update(GroupRequest $request,Group $group){
        if ($group->branch_id != getCurrentUserBranchData()->branch_id){
            abort(404);
        }
        $validated = $request->validated();
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;
        Group::find($group->id)->update($validated);
        Group::UploadFile($group, ['method'=>'update']);
        return Active::Updated($group->name);
    }

    public function destroy(Group $group){
        Group::where('id', $group->id)->where('branch_id',getCurrentUserBranchData()->branch_id)->SoftTrash();
        return Active::Deleted($group->name);
    }

    public function restore($group){
        Group::where('id', $group)->where('branch_id',getCurrentUserBranchData()->branch_id)->RestoreFromTrash();
        $group = Group::where('id', $group)->first();
        return Active::Restored($group->name);
    }

}
