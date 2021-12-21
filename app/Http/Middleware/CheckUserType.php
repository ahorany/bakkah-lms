<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserType
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
        if(isset(\auth()->user()->roles()->first()->id) && auth()->user()->roles()->first()->id == 3 ){
            abort(404);
        }

//        if(auth()->user()->user_type == 41) {
//            abort(404);
//        }

        return $next($request);
    }
}
