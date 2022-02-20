<?php

namespace App\Http\Middleware;

use App\Traits\Api\ApiResponseTrait;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiCheckAuth
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next ,$guard = 'api')
    {
        if($guard != null){
            auth()->shouldUse($guard); //should you user guard / table
//            $token = $request->header('auth-token');
//            $request->headers->set('auth-token', (string) $token, true);
//            $request->headers->set('Authorization', 'Bearer '.$token, true);
            try {
                $user = JWTAuth::parseToken()->authenticate();
            }catch (TokenExpiredException $e) {
                return  $this->returnErrorResponse(401,'Unauthenticated User');
            }catch (JWTException $e) {
                return  $this->returnErrorResponse(403, 'token_invalid '.$e->getMessage());
            }catch (\Exception $exception){
                return  $this->returnErrorResponse(401,'Unauthenticated User');
            }
        }
        return $next($request);
    }
}
