<?php

namespace App\Http\Middleware;

use Closure;

class RecaptchaMiddleware
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
        $go_request = $next($request);
        if(strtolower($request->method())=="post" && env('reCAPTCHA_RUN')==true){

            if($request->has('recaptcha_response')) {

                $result = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".env('reCAPTCHA_secret_key')."&response=".$request->recaptcha_response);
                $r = json_decode($result);
                // return redirect()->back()->withErrors('status', 'Recaptcha failed. Please try again.');
                // dd($r->score);
                if ($r->success == true && $r->score > 0.5) {
                    return $go_request;
                }
                else{
                    return redirect()->back()->withErrors('status', 'Recaptcha failed. Please try again.');
                }
            }
        }
        return $go_request;
    }
}
