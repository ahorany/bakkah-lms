<?php

namespace App\Http\View\Composers;
use App\Constant;
use App\Helpers\Models\Training\CartHelper;
use App\Infastructure;
use App\Infrastructure;
use App\Models\Admin\Post;
use App\Models\Training\Course;
use App\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class EducationComposer
{
    public function compose(View $view){

        // $DateTimeNow = DateTimeNow();



       $user_sidebar = Cache::rememberForever('user_sidebar', function(){
           return Infastructure::where('type', 'user_aside')
           ->orderBy('order')
           ->select('id', 'title', 'route_name', 'route_param', 'icon', 'order')
           ->get();
       });
       $view->with('user_sidebar', $user_sidebar);

       $user_sidebar_courses = User::whereId(auth()->id())->with(['courses'])->first();

       $view->with('user_sidebar_courses', $user_sidebar_courses);

        $asides = Infastructure::where('type', 'aside')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        $infastructures = Infastructure::where('type', 'aside')
            ->whereNotNull('parent_id')
            ->orderBy('order')
            ->get();

        $user_pages = Infrastructure::join('infrastructure_role', 'infrastructure_role.infrastructure_id', 'infastructures.id')
            ->join('role_user', 'role_user.role_id', 'infrastructure_role.role_id')
            ->whereNull('infastructures.parent_id')
            ->where('user_id', auth()->user()->id)
            ->distinct('infrastructures.id')
            ->select('infastructures.*')
            ->get();

        $user_pages_child = Infrastructure::join('infrastructure_role', 'infrastructure_role.infrastructure_id', 'infastructures.id')
            ->join('role_user', 'role_user.role_id', 'infrastructure_role.role_id')
            ->whereNotNull('infastructures.parent_id')
            ->where('user_id', auth()->user()->id)
            ->distinct('infastructures.id')
            ->select('infastructures.*')
            ->get();

        $view->with('asides', $asides);
        $view->with('infastructures', $infastructures);
        $view->with('user_pages', $user_pages);
        $view->with('user_pages_child', $user_pages_child);
//        $navbar_campaign = Post::where('post_type', 'navbar-campaign')
//            ->where('post_date', '<', $DateTimeNow)
//            ->where('date_to', '>', $DateTimeNow)
//            ->lang()
//            ->select('id', 'title', 'slug', 'url', 'details', 'date_to')
//            ->first();
//        // dd($navbar_campaign);
//        $view->with('navbar_campaign', $navbar_campaign);
//
//        $modal_campaign = Post::where('post_type', 'modal-campaign')
//            ->where('post_date', '<', $DateTimeNow)
//            ->where('date_to', '>', $DateTimeNow)
//            ->lang()
//            ->select('id', 'title', 'slug', 'url')
//            ->first();
//        $view->with('modal_campaign', $modal_campaign);

//        $course_menus = Cache::rememberForever('course_menus', function () {
//            return Constant::with('postMorph.postable:title,slug,id,show_in_website')
//            ->where('post_type', 'course')
//            ->where('id', '!=', 378)
//            ->orderBy('order')
//            ->select('id', 'name', 'post_type', 'slug')
//            ->get();
//        });
//        $view->with('course_menus', $course_menus);

//        $education_footer_menus = Cache::rememberForever('education_footer_menus', function(){
//            return Infastructure::where('type', 'education_footer_menu')
//            ->orderBy('order')
//            ->select('id', 'title', 'route_name', 'route_param', 'icon', 'order')
//            ->get();
//        });
//        $view->with('education_footer_menus', $education_footer_menus);

        //Footer
//        $footerRecentArticles = [];
//        $footerRecentArticles = Post::lang()
//            ->where('post_type', 'knowledge-center')
//            ->latest()
//            ->take(4)
//            ->select('id', 'title', 'slug', 'post_date')
//            ->get();
//        $view->with('footerRecentArticles', $footerRecentArticles);


    }
}
