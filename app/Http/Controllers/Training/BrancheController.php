<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Helpers\Active;
use App\Http\Requests\Training\BrancheRequest;
use App\Http\Requests\Training\CourseRequest;
use App\Models\Admin\Partner;
use App\Models\Training\Branche;
use App\Models\Training\Course;
use App\Constant;
use App\Timezone;
use App\Models\Training\Role;
use Illuminate\Database\Eloquent\Builder;

class BrancheController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.branches.index');


        Active::$namespace = 'training';
        Active::$folder = 'branches';
    }

    public function index(){
        $post_type = 'branches';
        $trash = GetTrash();
        $branches = Branche::query();
        $count = $branches->count();
        $branches = $branches->page();
        return Active::Index(compact('branches', 'count', 'post_type', 'trash'));
    }

    public function create(){
        $timezones = Timezone::get();
        return Active::Create([
            'timezones'=>$timezones,
        ]);
    }

    public function store(BrancheRequest $request){

        $validated = $request->validated();
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;

        $branche= Branche::create($validated);


       $role =  Role::create(['name' => 'Admin','guard_name' => 'web','role_type_id' => 510,'branch_id' => $branche->id ,'icon' => 'admin.svg' ]);
       $permissions = ["11","12","13","14","15","16","17","18","19","20","21","22",
                       "23","24","25","26","27","28","29","30","31", "36","37" ];
       $role->syncPermissions($permissions);


        $role =  Role::create(['name' => 'Instructor','guard_name' => 'web','role_type_id' => 511,'branch_id' => $branche->id ,'icon' => 'instructor.svg' ]);
        $role->syncPermissions(["21"]);

        Role::create([
            'name' => 'trainee',
            'guard_name' => 'web',
            'role_type_id' => 512,
            'branch_id' => $branche->id ,
            'icon' => 'trainee.svg'
        ]);


        return Active::Inserted($branche->name);
    }

    public function edit(Branche $branch){
        $timezones = Timezone::get();
        return Active::Edit([
            'eloquent'=>$branch,
            'timezones'=>$timezones,
        ]);
    }

    public function update(BrancheRequest $request,Branche $branch){
        $validated = $request->validated();
        $validated['updated_by'] = auth()->user()->id;
        $validated['active'] = request()->has('active')?1:0;
        Branche::find($branch->id)->update($validated);
        $fileName = Branche::UploadFile($branch, ['method'=>'update']);
        $user_branch = session()->get('user_branch');
        if ($branch->id == $user_branch->branch_id){
            $user_branch->main_color = $validated['main_color'];
            $user_branch->file = $fileName;
        }

        return Active::Updated($branch->name);
    }

    public function destroy(Branche $branch){
        Branche::where('id', $branch->id)->SoftTrash();
        return Active::Deleted($branch->name);
    }

    public function restore($branch){
        Branche::where('id', $branch)->RestoreFromTrash();
        $branch = Branche::where('id', $branch)->first();
        return Active::Restored($branch->name);
    }

}
