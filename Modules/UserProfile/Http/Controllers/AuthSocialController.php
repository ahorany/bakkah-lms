<?php

namespace Modules\UserProfile\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialController extends Controller
{
    public function redirect($service){
        return Socialite::driver($service)->redirect();
    }

    public function callback($service){
        $user = Socialite::with($service);
        dd($user);
    }
}
