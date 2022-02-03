<?php

namespace App\Http\Controllers\Training;

use App\Helpers\Active;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Infrastructure;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

        // ======= Not Trainees ========
        $roles = Role::where('id', '<>', 3)->with('permissions');

        if(!is_null(request()->user_search)) {
            $roles = $roles->where(function($query){
                $query->where('name', 'like', '%'.request()->user_search.'%');
            });
        }

    	$count = $roles->count();
    	$roles = $roles->paginate(100);
    	// $roles = $roles->page();

        return Active::Index(compact('roles', 'count', 'post_type', 'trash'));
    }

    public function create()
    {
        $role = new Role;
        $permission = Permission::get();
        return Active::Create(['eloquent'=>$role, 'object'=>ٌRole::class,'permission' => $permission]);

//        $pages = Infrastructure::where('type', 'aside')
//                ->whereNull('parent_id')
//                ->orderBy('order')
//                ->get();
//
//        $infrastructures = Infrastructure::where('type', 'aside')
//                ->whereNotNull('parent_id')
//                ->orderBy('order')
//                ->get();

//        $role = new Role;
//        return Active::Create(['eloquent'=>$role, 'object'=>ٌRole::class, 'pages'=>$pages, 'infrastructures'=>$infrastructures]);
    }

    public function store(RoleRequest $request){
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        return Active::Inserted($role->name);
    }

    public function edit(Role $role)
    {
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id",$role->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return Active::Edit(['eloquent'=>$role, 'permission'=>$permission,'rolePermissions' => $rolePermissions]);
    }


    public function update(RoleRequest $request, Role $role){
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        return Active::Updated($role->name);
    }

    public function destroy(Role $role, Request $request){
        Role::where('id', $role->id)->SoftTrash();
        return Active::Deleted($role->name);
    }

    public function restore($role){
        Role::where('id', $role)->RestoreFromTrash();
        $role = Role::where('id', $role)->first();
        return Active::Restored($role->trans_name);
    }

}
