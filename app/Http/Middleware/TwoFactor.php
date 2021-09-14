<?php

namespace App\Http\Middleware;

use Closure;

class TwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(env('Two_Factor_Login')==true)
        {
            $user = auth()->user();

            if(auth()->check() && $user->two_factor_code)
            {
                // if($user->two_factor_expires_at->lt(now()))

                if($user->two_factor_expires_at < now())
                {
                    $user->resetTwoFactorCode();
                    auth()->logout();

                    return redirect()->route('login')->withMessage('The two factor code has expired. Please login again.');
                }
                // dd($request->is('twofactor/verify'));
                if(!$request->is('twofactor/verify'))
                {
                    return redirect()->route('twofactor.verify.index');
                }
            }
        }

        return $next($request);
    }
}
