<?php

namespace App\Http\Controllers\Training;

use App\Helpers\Active;
use App\Http\Requests\Training\RoleRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Models\Training\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training.roles.index', ['only' => ['index']]);
        $this->middleware('permission:roles.create', ['only' => ['create','store']]);
        $this->middleware('permission:roles.edit', ['only' => ['edit','update']]);
        $this->middleware('permission:roles.delete', ['only' => ['destroy']]);

        Active::$namespace = 'training';
        Active::$folder = 'roles';
    }

    public function index()
    {

    	$post_type = GetPostType('roles');
    	$trash = GetTrash();

        $roles = Role::where(function ($q){
            $q->where('branch_id',getCurrentUserBranchData()->branch_id??1);
        })->with('permissions');


        if(!is_null(request()->role_search)) {
            $roles = $roles->where(function($query){
                $query->where('name', 'like', '%'.request()->role_search.'%');
            });
        }

    	$count = $roles->count();
    	$roles = $roles->page();

        return Active::Index(compact('roles', 'count', 'post_type', 'trash'));
    }

    public function create()
    {
        $role = new Role;
        $permission = Permission::where('name','<>','training.branches.index')->get();
        return Active::Create(['eloquent'=>$role, 'object'=>ٌRole::class,'permission' => $permission]);
    }

    public function store(RoleRequest $request){
        $permissions = $request->input('permission');
        if (($key = array_search(34, $permissions)) !== false) {
            array_splice($permissions, $key, 1);
        }

        $role = Role::create(['name' => $request->input('name'),
                              'branch_id' => getCurrentUserBranchData()->branch_id??1]);

        $role->syncPermissions($permissions);
        return Active::Inserted($role->title);
    }

    public function edit(Role $role)
    {
        if ( is_super_admin() == false && ($role->branch_id != getCurrentUserBranchData()->branch_id) ){
            abort(404);
        }

        $permission = Permission::where('name','<>','training.branches.index')->get();
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id",$role->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return Active::Edit(['eloquent'=>$role, 'permission'=>$permission,'rolePermissions' => $rolePermissions]);
    }


    public function update(RoleRequest $request, Role $role){
        if (is_super_admin() == false && ($role->branch_id != getCurrentUserBranchData()->branch_id) ){
            abort(404);
        }

        $permissions = $request->input('permission');
        if (($key = array_search(34, $permissions)) !== false) {
            array_splice($permissions, $key, 1);
        }

        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($permissions);
        return Active::Updated($role->name);
    }

    public function destroy(Role $role, Request $request){
        if (is_super_admin() == false && ($role->branch_id != getCurrentUserBranchData()->branch_id) ){
            abort(404);
        }

        $role_type_ids = [510,511,512];
        if(in_array($role->role_type_id, $role_type_ids)){
            abort(404);
        }

        Role::where('id', $role->id)->SoftTrash();
        return Active::Deleted($role->title);
    }

    public function restore($role){
        $role_branch_id = Role::withTrashed()->select('branch_id')->whereId($role)->first()->branch_id;
        if (!$role_branch_id){
            abort(404);
        }
        if ( is_super_admin() == false && ($role_branch_id != getCurrentUserBranchData()->branch_id) ){
            abort(404);
        }

        Role::where('id', $role)->RestoreFromTrash();
        $role = Role::where('id', $role)->first();
        return Active::Restored($role->title);
    }

}
