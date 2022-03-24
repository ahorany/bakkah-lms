<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        Gate::before(function ($user, $ability) {
             return is_super_admin();
        });



        Gate::define('preview-gate', function ($user) {
            $branch_role_admin  = \App\Models\Training\Role::where('branch_id',getCurrentUserBranchData()->branch_id??1)
                ->where('role_type_id',510)->first();

            if($user->roles->first()->id == $branch_role_admin->id && request()->has('preview')){
                return true;
            }
            return false;
        });
    }
}
