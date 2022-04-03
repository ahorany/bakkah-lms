<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Training\Branche;
use App\Notifications\TwoFactorCode;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    protected function authenticated(Request $request, $user)
    {

        $user_branch = DB::select('SELECT branches.title ,  user_branches.id , user_branches.branch_id , user_branches.user_id ,user_branches.name , user_branches.bio , user_branches.expire_date , user_branches.delegation_role_id
                                         FROM `user_branches`
                                         INNER JOIN branches ON branches.id = user_branches.branch_id AND branches.deleted_at IS NULL
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
        // dd(getCurrentUserBranchData());
    }


}
