<?php

namespace App\Http\View\Composers;
use App\Infrastructure;
use App\User;
use Illuminate\View\View;

class AsideComposer
{
	public function compose(View $view){

        $user_pages = Infrastructure::select('Infrastructures.*')->get();
        $view->with('user_pages', $user_pages);


        $user_sidebar_courses = User::whereId(auth()->id())->with(['courses'])->first();
        $view->with('user_sidebar_courses', $user_sidebar_courses);
	}
}
