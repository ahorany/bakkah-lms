<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
// use http\Url;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;

use Spatie\Sitemap\Tags\Url;
use Psr\Http\Message\UriInterface;

class HomeController extends Controller
{
    public function index(){
//        SitemapGenerator::create('http://localhost:8000')
//            ->writeToFile(public_path('sitemap.xml'));
//        $this->sitemap('http://127.0.0.1:8000');
        return view(FRONT.'.home.index');
    }

    public function sitemap($ur=null){

        // $ur = 'https://bakkah.com';
        $ur = env('APP_URL');
        // SitemapGenerator::create('https://bakkah.com')
        //   ->writeToFile(public_path('sitemap.xml'));
        $array = [];
        SitemapGenerator::create($ur)
        ->hasCrawled(function (Url $url) use(&$array) {

            $path = $url->path();
            if(substr($path, -1)=='/'){
                $path = substr($path, 0, -1);
            }

            if(in_array($path, $array)){
                return;
            }
            else {
                array_push($array, $path);

                if(strpos($path, 'ar')!==false || strpos($path, 'en')!==false || strpos($path, 'category')!==false || strpos($path, 'knowledge-center')!==false ||
                 strpos($path, '.pdf')!==false || strpos($path, 'consulting-insights')!==false ||
                 strpos($path, 'register')!==false || strpos($path, '.xlsx')!==false ||
                 strpos($path, 'consulting-services')!==false || strpos($path, 'self-study')!==false || strpos($path, '.jpg')!==false){
                    return;
                }

                return $url;
            }
            return;
       })
        ->writeToFile(public_path('sitemap.xml'));
        dd('Done');

        $fileName = 'sitemap.xml';
        $this->path = public_path('/sitemap/');

        ini_set("memory_limit", -1);
        set_time_limit(0);
        ini_set("max_execution_time", 0);
        ignore_user_abort(true);

        if(file_exists($this->path.$fileName)){
            chmod($this->path, 0777);
            chmod($this->path.$fileName, 0777);
            rename($this->path.$fileName, $this->path.'sitemap-old'.date('D-d-M-Y h-s').'xml');
        }

        SitemapGenerator::create($ur)
            ->hasCrawled(function (Url $url){
                $priorityUrl = [
                    'consulting'
                ];

                if($url->segment(1)==$priorityUrl[0]){
                    $url->setPriority(1.0)
                        ->setLastModificationDate(Carbon::now());
                }
                else{
                    $url->setPriority(0.0)
                        ->setLastModificationDate(Carbon::now());
                }

                return $url;
            })
            ->writeToFile($this->path.$fileName);
    }
}
