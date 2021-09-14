<?php

namespace App\Helpers;


use App\Models\Admin\Post;
use Illuminate\Http\Exceptions\HttpResponseException;

class RedirectTransSlug {


    public static function checkPostSlug($slug,$locale,$basic_id,$redirect_route_name){

        if($locale != app()->getLocale()){

                $post = Post::where('basic_id', $basic_id)->where('locale',app()->getLocale())
                    ->select( 'slug', 'basic_id')
                    ->first();

                if($post)
                {
                    if($post->slug != $slug)
                    {
                        self::customRedirect($redirect_route_name,[$post->slug]);
                    }
                }
            }

            $post = Post::with(['upload:uploadable_id,uploadable_type,file', 'postMorph.constant'])
            ->where('locale',app()->getLocale())->whereSlug($slug)->first();

           return $post;
    }

    public static function customRedirect($routeName,$params){

        throw new HttpResponseException(redirect(route($routeName,$params)));
    }
}
