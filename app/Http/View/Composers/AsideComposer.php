<?php

namespace App\Http\View\Composers;
use App\Infastructure;
use App\Infrastructure;
use App\User;
use Illuminate\View\View;

class AsideComposer
{
	public function compose(View $view){

    //    $asides = Infastructure::where('type', 'aside')
    //    ->whereNull('parent_id')
    //    ->orderBy('order')
    //    ->get();

    //    $infastructures = Infastructure::where('type', 'aside')
    //    ->whereNotNull('parent_id')
    //    ->orderBy('order')
    //    ->get();

        $user_pages = Infrastructure::join('infrastructure_role', 'infrastructure_role.infrastructure_id', 'infastructures.id')
        ->join('role_user', 'role_user.role_id', 'infrastructure_role.role_id')
        ->whereNull('infastructures.parent_id')
        ->where('user_id', auth()->user()->id)
        ->distinct('infrastructures.id')
        ->select('infastructures.*')
        ->get();
        // dd($user_pages);
        $user_pages_child = Infrastructure::join('infrastructure_role', 'infrastructure_role.infrastructure_id', 'infastructures.id')
        ->join('role_user', 'role_user.role_id', 'infrastructure_role.role_id')
        ->whereNotNull('infastructures.parent_id')
        ->where('user_id', auth()->user()->id)
        ->distinct('infastructures.id')
        ->select('infastructures.*')
        ->get();
        // dd($user_pages_child);
        // $view->with('asides', $asides);
        // $view->with('infastructures', $infastructures);

        $view->with('user_pages', $user_pages);
        $view->with('user_pages_child', $user_pages_child);

        $user_sidebar_courses = User::whereId(auth()->id())->with(['courses'])->first();
        $view->with('user_sidebar_courses', $user_sidebar_courses);
	}
}
