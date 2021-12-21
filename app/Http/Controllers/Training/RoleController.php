<?php

namespace App\Http\Controllers\Training;

use App\Helpers\Active;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Infrastructure;
use App\Models\Admin\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        Active::$namespace = 'training';
        Active::$folder = 'roles';
    }

    public function index()
    {

    	$post_type = GetPostType('roles');
    	$trash = GetTrash();

        // ======= Not Trainees ========
        $roles = Role::where('id', '<>', 3)->with('infrastructures');

        if(!is_null(request()->user_search)) {
            $roles = $roles->where(function($query){
                $query->where('name', 'like', '%'.request()->user_search.'%');
            });
        }

    	$count = $roles->count();
    	$roles = $roles->page();

        return Active::Index(compact('roles', 'count', 'post_type', 'trash'));
    }

    public function create()
    {
        $pages = Infrastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();

        $infrastructures = Infrastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->orderBy('order')
                ->get();

        $role = new Role;
        return Active::Create(['eloquent'=>$role, 'object'=>ٌRole::class, 'pages'=>$pages, 'infrastructures'=>$infrastructures]);
    }

    public function store(RoleRequest $request){

    	$validated = $request->validated();
        $validated['name'] = null;
        $validated['created_by'] = auth()->user()->id;
        $validated['updated_by'] = auth()->user()->id;

        $role = Role::create($validated);

        $role->infrastructures()->attach(request()->pages);

        return Active::Inserted($role->trans_name);
    }

    public function edit(Role $role)
    {
        $pages = Infrastructure::where('type', 'aside')
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();

        $infrastructures = Infrastructure::where('type', 'aside')
                ->whereNotNull('parent_id')
                ->orderBy('order')
                ->get();

        return Active::Edit(['eloquent'=>$role, 'pages'=>$pages, 'infrastructures'=>$infrastructures]);
    }

    public function update(RoleRequest $request, Role $role){

        $validated = $request->validated();
        $validated['name'] = null;
        $validated['updated_by'] = auth()->user()->id;

        $role->update($validated);

        $role->infrastructures()->sync(request()->pages);

        return Active::Updated($role->trans_name);
    }

    public function destroy(Role $role, Request $request){
        Role::where('id', $role->id)->SoftTrash();
        return Active::Deleted($role->trans_name);
    }

    public function restore($role){
        Role::where('id', $role)->RestoreFromTrash();
        $role = Role::where('id', $role)->first();
        return Active::Restored($role->trans_name);
    }

}
