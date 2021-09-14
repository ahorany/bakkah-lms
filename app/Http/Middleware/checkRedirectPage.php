<?php

namespace App\Http\Middleware;

use App\Models\Admin\Redirect;
use Closure;
use Illuminate\Support\Facades\URL;

class checkRedirectPage
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

        $current_url = URL::current();
        $redirect = Redirect::where('source_url',$current_url)->whereHas('type')->first();
        if($redirect){
           return redirect()->away($redirect->destination_url);
        }

        return $next($request);
    }
}
