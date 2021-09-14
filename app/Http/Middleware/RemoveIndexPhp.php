<?php

namespace App\Http\Middleware;

use Closure;

class RemoveIndexPhp
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
        $searchFor = "index.php";
        $strPosition = strpos($request->fullUrl(), $searchFor);
        if ($strPosition !== false) {
            $url = substr($request->fullUrl(), $strPosition + strlen($searchFor) + 1);
            return redirect(env('APP_URL') . $url, 301);
        }

        $searchFor = "www.";
        $strPosition = strpos($request->fullUrl(), $searchFor);
        if ($strPosition !== false) {
            $url = substr($request->fullUrl(), $strPosition + strlen($searchFor));
            return redirect('https://'.$url, 301);
        }
        return $next($request);
    }
}
