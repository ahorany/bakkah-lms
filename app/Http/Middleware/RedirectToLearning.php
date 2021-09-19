<?php

namespace App\Http\Middleware;

use Closure;

class RedirectToLearning
{
    public function handle($request, Closure $next)
    {
        $old_key = 'bakkah.net.sa';
        $old_url = 'https://bakkah.net.sa';
        $new_url = 'https://bakkah.com';

        /*$old_key = '127';
        $old_url = 'http://127.0.0.1:8000';
        $new_url = 'http://localhost:8000';*/
        $old_path = strpos($request->fullUrl(), $old_key, 1);
        if($old_path!=false)
        {
            // $searchFor = ['learning', 'sessions', 'hot-deals', 'knowledge-center', 'redirect_to_dontcom'];
            $searchFor = ['learning', 'sessions', 'hot-deals', 'category', 'category/knowledge-center', 'knowledge-center/', 'redirect_to_dontcom'];//category
            if ($this->strposa($request->fullUrl(), $searchFor, 1)) {

                $res = strpos($request->fullUrl(), 'learning', 1);
                if($res!=false){
                    $new_path = $new_url;
                }
                else{
                    $new_path = str_replace($old_url, $new_url, $request->fullUrl());
                }
                return redirect()->away($new_path, 301);
            }
            // else
            // {
            //     echo 'false';
            // }
        }
        return $next($request);
    }

    // https://stackoverflow.com/questions/6284553/using-an-array-as-needles-in-strpos
    private function strposa($haystack, $needles=array(), $offset=0) {
        $current_needle=false;
        foreach($needles as $needle) {
            $res = strpos($haystack, $needle, $offset);
            if ($res !== false) {
                $current_needle = $needle;
            }
        }
        return $current_needle;
    }
}
