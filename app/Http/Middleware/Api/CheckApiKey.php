<?php

namespace App\Http\Middleware\Api;

use App\Models\Admin\Redirect;
use Closure;
use Illuminate\Support\Facades\URL;

class CheckApiKey
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
        if($request->api_key != env('STATIC_API_TOKEN')){
            return response()->json([
                'status' => 'Unauthorized',
                'code' => 401 ,
                'message' => "Error: Api Token Invalid" ,
            ],401 );
        }

        return $next($request);
    }
}
