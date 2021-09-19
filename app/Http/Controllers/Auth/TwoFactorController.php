<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\User;

class TwoFactorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('twofactor', ['except' => ['store','resend','verify']]);
        // $this->middleware('twofactor', ['only' => ['store']]);
        // $this->middleware(['auth', 'twofactor']);
    }

    public function verify()
    {
        // $verify_login = null;
        // if(session()->has('verify_login'))
        // {
        //     $verify_login = session()->get('verify_login')??null;
        // }

        // if($verify_login){
        //     return redirect()->route('admin.home');
        // }else{
        //     // return view('auth.twoFactor');
            $user = auth()->user();
            if(auth()->check() && !$user->two_factor_code)
            {
                return redirect()->route('admin.home');
            }else{
                return view('auth.twoFactor');
            }
        // }
    }

    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'integer|required',
        ]);

        $user = auth()->user();

        if($request->input('two_factor_code') == $user->two_factor_code)
        {
            $user->resetTwoFactorCode();

            // session()->put('verify_login', 'true');

            return redirect()->route('admin.home');
        }

        return redirect()->back()->withErrors(['two_factor_code' => 'The two factor code you have entered does not match']);
    }

    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        $user->notify(new TwoFactorCode());

        return redirect()->back()->withMessage('The two factor code has been sent again');
    }
}
