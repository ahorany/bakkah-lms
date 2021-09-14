<?php

namespace App\Http\View\Composers;
use App\Constant;
use App\Infastructure;
use App\Models\Admin\Post;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class ConsultingComposer
{
    public function compose(View $view){

        $consulting_navbar_menus = Infastructure::where('type', 'consulting_navbar_menu')
        ->orderBy('order')
        ->get();
        // $consulting_navbar_menus = Cache::rememberForever('consulting_navbar_menus', function () {
        //     return Infastructure::where('type', 'consulting_navbar_menu')
        //     ->orderBy('order')
        //     ->get();
        // });
        $view->with('consulting_navbar_menus', $consulting_navbar_menus);

//        $navbar_campaign = Post::where('post_type', 'navbar-campaign')
//            ->whereDate('post_date', '>', Carbon::now())
//            ->lang()
//            ->first();
//        $view->with('navbar_campaign', $navbar_campaign);
//
//        //
        /*$course_menus = Constant::with('postMorph.postable.upload')
            ->where('post_type', 'course')
            ->orderBy('order')
            ->get();
        $view->with('course_menus', $course_menus);*/

        $consulting_footer_menus = Infastructure::where('type', 'consulting_footer_menu')
        ->orderBy('order')
        ->get();
        // $consulting_footer_menus = Cache::rememberForever('consulting_footer_menus', function () {
        //     return Infastructure::where('type', 'consulting_footer_menu')
        //     ->orderBy('order')
        //     ->get();
        // });
        $view->with('consulting_footer_menus', $consulting_footer_menus);

        //Footer
        $footerRecentArticles = Post::lang()
            ->where('post_type', 'consulting-insights')
            ->latest()
            ->take(3)
            ->get();
        $view->with('footerRecentArticles', $footerRecentArticles);
    }
}
