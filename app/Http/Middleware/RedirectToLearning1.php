<?php

namespace App\Http\Middleware;

use Closure;

class RedirectToLearning1
{
    public function handle($request, Closure $next)
    {
        $old_key = 'bakkah.net.sa';
        $old_url = 'https://testing.bakkah.net.sa';
        $new_url = 'https://bakkah.com';

        $old_url_name = "testing.bakkah.net.sa";
        $new_url_name = "bakkah.com";

        $old_path = strpos($request->fullUrl(), $old_key, 1);
        if($old_path!=false)
        {
            // $searchFor = ['learning', 'sessions', 'hot-deals', 'knowledge-center', 'redirect_to_dontcom'];
            $searchFor = ['learning', 'sessions', 'hot-deals', 'category', 'category/knowledge-center', 'redirect_to_dontcom'];//category
            if ($this->strposa($request->fullUrl(), $searchFor, 1)) {

                $res = strpos($request->fullUrl(), 'learning', 1);
                if($res!=false){
                    $new_path = $new_url;
                }
                else{
                    // dump($old_url);
                    // dump($new_url);
                    // dump($request->fullUrl());
                    // $new_path = str_replace($old_url, $new_url, $request->fullUrl());
                    // dump($new_path);

                    $new_path = str_replace($old_url_name, $new_url_name, $request->fullUrl());
                    // dump($new_path);
                }
                // dd($new_path);
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
