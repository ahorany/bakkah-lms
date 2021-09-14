<?php

namespace App\Http\Middleware;

use Closure;

class CheckPageMiddleware
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
        // $routes_name = auth()->user()->roles[0]->infrastructures()->select('route_name', 'route_param')->get();
        // foreach ($routes_name as  $route) {
        //     if($route->route_name) {
        //         $args = [];
        //         if(!is_null($route->route_param)){
        //         $args = array_merge($args, json_decode($route->route_param, true));
        //         }
        //         dump(route($route->route_name, $args));
        //     }
        // }
        // exit;
        return $next($request);
    }
}
