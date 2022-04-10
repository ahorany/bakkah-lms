<?php

namespace App\Http\View\Composers;
use App\Infrastructure;
use App\Models\Training\Role;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AsideComposer
{
	public function compose(View $view){
       $user = auth()->user();

        $user_pages = Infrastructure::all();
        $view->with('user_pages', $user_pages);

        $role = $user->roles()->first();
        $view->with('role', $role);

        if($role->role_type_id != 510){
            $user_sidebar_courses = DB::select("SELECT courses.id , courses.training_option_id , courses.title  FROM `courses_registration`
                                                  INNER JOIN courses ON courses.id = courses_registration.course_id
                                                          AND courses.deleted_at IS NULL
                                                          AND courses.branch_id = ".getCurrentUserBranchData()->branch_id."
                                                  WHERE courses_registration.user_id =".$user->id);
            $view->with('user_sidebar_courses', $user_sidebar_courses);
        }




        if (is_super_admin()){
            $user_branches = DB::select('SELECT * FROM branches');
        }else{
            $user_branches = DB::select('SELECT  branches.* FROM `user_branches`
                                INNER JOIN branches ON branches.id = user_branches.branch_id AND branches.deleted_at IS NULL
                                WHERE user_branches.user_id = '.auth()->id().' AND user_branches.deleted_at IS NULL
                                ORDER BY user_branches.id');
        }
        $view->with('user_branches', $user_branches);

        if (!is_super_admin()) {
            $headerRoles = Role::select('id', 'name', 'icon')->where('branch_id', getCurrentUserBranchData()->branch_id ?? 1)->get();
            $view->with('headerRoles', $headerRoles);
        }

    }

}
