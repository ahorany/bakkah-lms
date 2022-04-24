<?php

namespace App\Listeners;

use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle($event)
    {
        $user = auth()->user();
        $user_branch = DB::select('SELECT branches.title , branches.main_color , branches.description ,  user_branches.id , user_branches.branch_id , user_branches.user_id ,user_branches.name , user_branches.bio , user_branches.expire_date , user_branches.delegation_role_id , uploads.file
        FROM `user_branches`
        INNER JOIN branches ON branches.id = user_branches.branch_id
                            AND branches.deleted_at IS NULL
        LEFT JOIN uploads ON uploads.uploadable_id = branches.id
                            AND uploads.deleted_at IS NULL
                            AND uploads.uploadable_type = "App\\\Models\\\Training\\\Branche"
        WHERE user_branches.user_id = '.$user->id.' AND user_branches.deleted_at IS NULL
        ORDER BY user_branches.id DESC LIMIT 1');


        if(isset($user_branch[0])) {
            session()->put('user_branch',$user_branch[0]);
        }else{
            return auth()->logout();
        }

        $data = DB::select('SELECT * FROM model_has_roles
                                  WHERE model_has_roles.role_id = 4 AND model_has_roles.model_id = '.auth()->id());

        session()->put('is_super_admin',isset($data[0]));
        User::where('id',$user->id)->update([
            'last_login' => Carbon::now()
        ]);
    }
}
