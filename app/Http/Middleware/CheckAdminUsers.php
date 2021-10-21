<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdminUsers
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
        //dd(auth()->user()->user_type);
        if(auth()->user()->user_type == 41) {
            return redirect()->route('user.home');
        }
        return $next($request);
    }
}
