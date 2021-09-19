<?php

namespace App\Traits\Post;

// use Illuminate\Support\Facades\Cache;

trait SliderTrait
{
    public static function GetSlider($post_type, $take, $order_field='posts.post_date', $order='desc'){

        $coin_id = GetCoinId();
        // if(Cache::has('coinID_'.request()->ip())){
        //     $coin_id = Cache::get('coinID_'.request()->ip());
        // }

        // $posts = self::where('posts.post_type', $post_type)->get();
        // dd($posts);


        $posts = self::where('posts.post_type', $post_type)
        ->join('uploads', function($join){
            $join->on('uploads.uploadable_id', 'posts.id')
            ->where('uploads.uploadable_type', 'App\Models\Admin\Post');
        })
        ->where('posts.post_date', '<=', DateTimeNow())
        // ->where('posts.coin_id', $coin_id)
        // ->orWhere('posts.coin_id', -1)
        ->lang('posts.')
        ->take($take)
        ->orderBy($order_field, $order)
        ->select('posts.id', 'posts.title', 'posts.excerpt', 'posts.url', 'uploads.file'
        , 'uploads.title as upload_title')
        // ->whereIn('posts.country_id', [$GetCountryCode, -1])
        ->get();
        // if($coin_id==335){
        //     dump($posts);
        // }

        // dd($posts);
        return $posts;
    }
}
