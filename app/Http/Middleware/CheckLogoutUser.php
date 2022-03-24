<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogoutUser
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
        $user = auth()->user();

        if ($user && $user->is_logout) {
            $user->is_logout = 0;
            $user->save();
            auth()->logout();
            return redirect()->route('login');
        }
        return $next($request);
    }
}
