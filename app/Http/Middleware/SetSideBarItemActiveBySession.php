<?php

namespace App\Http\Middleware;

use Closure;

class SetSideBarItemActiveBySession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$active_route_name = "user.home")
    {
        session()->put('active_sidebar_route_name',$active_route_name);
        return $next($request);
    }
}
