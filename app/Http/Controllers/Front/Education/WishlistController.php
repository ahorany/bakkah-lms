<?php

namespace App\Http\Controllers\Front\Education;

use Illuminate\Http\Request;
use App\Models\Training\Wishlist;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    function wishlist()
    {
        return view('front.education.products.wishlists');
    }

    public function addToWishlist()
    {
        $training_option_id = request()->training_option_id;
        $session_id = request()->session_id;

        $wishlist = Wishlist::where('user_id', Auth::id())->where('training_option_id', $training_option_id);

        $wishlist_item = $wishlist->get();

        if($wishlist_item->count() > 0){
            $wishlist->delete();
        }else {
            $wishlist = Wishlist::create([
                'user_id'            => Auth::id(),
                'training_option_id' => $training_option_id,//??
                'session_id' => $session_id
            ]);
        }

        return 'done';
    }

    function wishlistItems()
    {
        if(Auth::check()) {
            $wishlists = Wishlist::whereNotNull('id')
            ->where('user_id', Auth::id())
            ->with(['trainingOption.course.upload'])
            ->orderBy('id', 'desc')
            ->get();

            return $wishlists;
        }
        return null;
    }

    function deleteWishlistItem()
    {
        if(request()->has('wishlist_id')) {
            $wishlist = Wishlist::where('id', request()->wishlist_id)->delete();
            return $wishlist;
        }
        return false;
    }

}
