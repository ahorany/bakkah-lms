<?php

namespace App\Http\Controllers\Front\Education;

use App\Helpers\Models\Training\CartHelper;
use App\Helpers\Models\Training\CartMasterHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Training\Cart;
use App\Models\Training\Course;
use App\Models\Training\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Training\CartMaster;
use App\Http\Controllers\Controller;
use App\Models\Training\CartFeature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Training\Discount\Discount;
use App\Helpers\Models\Training\SessionHelper;
use App\Helpers\Models\UserHelper;
use App\Models\Training\TrainingOptionFeature;
use App\Models\Training\Discount\DiscountDetail;

class CartController extends Controller
{
    public function __construct()
    {
        $this->path = FRONT . '.education';
    }

    public function cart()
    {
        if (auth()->check() && !auth()->user()->email_verified_at)
            return view('auth.verify');

        $CartHelper = new CartHelper();
        // $carts = $this->GetCarts();
        $cart_with_details = $CartHelper->Details();

        $cartMaster = $cart_with_details['cartMaster'];
        $carts = $cart_with_details['carts'];
        $features = $cart_with_details['features'];

        return view('front.education.products.cart', compact('cartMaster', 'carts', 'features'));
    }

    public function addCartItem()
    {
        $session_id = request()->session_id;
        $type = request()->type;

        $CartMasterHelper = new CartMasterHelper();
        $CartHelper = new CartHelper();
        $UserHelper = new UserHelper();
        $user_token = $UserHelper->user_token();

        $cartMaster = $CartMasterHelper->updateOrCreate($user_token);

        $cart = $CartHelper->updateOrCreate([
            'master_id'=>$cartMaster->id,
            'user_token'=>$user_token,
            'session_id' => $session_id,
            'type' => $type,
        ]);

        $CartMasterHelper->updateTotalAll($cart);
        $cart = $CartHelper->GetCart($cart->id);
        return $cart;
    }

    function addCartFeature()
    {
        $cart_id = request()->cart_id??18151;
        $cart = Cart::find($cart_id);

        $CartHelper = new CartHelper();
        $CartHelper->AddOrRemoveFeature($cart, request()->feature_id);

        $CartMasterHelper = new CartMasterHelper();
        $CartMasterHelper->updateTotalAll($cart);

        $CartMaster = $this->GetMaster($cart->master_id);
        $cart = $CartHelper->GetCart($cart->id);
        return [
            'CartMaster'=>$CartMaster,
            'cart'=>$cart,
        ];
        // CartMaster::UpdateTotal($cart);

        // $total = GetCartTotalPriceAfterVat($cart->master_id);

        // CartMaster::updateOrCreate([
        //     'user_token' => Cookie::get('user_token'),
        //     'payment_status' => 63,
        // ], [
        //     'invoice_amount' => NumberFormat($total),
        //     'total_after_vat' => NumberFormat($total),
        // ]);
        // CartMaster::UpdateSummation($cart->master_id);
        ////////////////////////////////////////////////////

        // $cart = Cart::find($cart_id);
        // $CartMaster = CartMaster::where('id', $cart->master_id)->first();
        return [
            'cart'=>$CartMaster['cart'],
            'CartMaster'=>$CartMaster['CartMaster'],
        ];
        // return [
        //     'cart'=>json_decode($cart),
        //     'CartMaster'=>$CartMaster,
        // ];
        // return 'done';
    }

    function deleteExpireCarts()
    {
        if (Auth::check()) {
            $carts = Cart::whereNotNull('id')
                ->where('user_id', Auth::id())
                ->where('payment_status', '!=', 68)
                ->whereNull('trashed_status')
                ->where('coin_id', session('coinID'))
                ->whereHas('session', function (Builder $query) {
                    $query->where('session_start_time', '<=', DateTimeNowAddHours());
                })
                ->orderBy('id', 'desc')
                ->delete();
        } else {
            // if(session('user_token')) {
            if (Cookie::get('user_token')) {
                $carts = Cart::whereNotNull('id')
                    // ->where('user_token', session('user_token'))
                    ->where('user_token', Cookie::get('user_token'))
                    ->where('payment_status', '!=', 68)
                    ->whereNull('trashed_status')
                    ->where('coin_id', session('coinID'))
                    ->whereHas('session', function (Builder $query) {
                        $query->where('session_start_time', '<=', DateTimeNowAddHours());
                    })
                    ->orderBy('id', 'desc')
                    ->delete();

                return $carts;
            }
        }
        return null;
    }

    function cartItems()
    {
        $this->deleteExpireCarts();
        $CartHelper = new CartHelper();
        $carts = $CartHelper->GetCarts()->get();
        // $carts = $this->GetCarts();
        return $carts;
    }

    function deleteCartItem()
    {
        if (request()->has('cart_id')) {
            $cart = Cart::find(request()->cart_id);

            $master_id = $cart->master_id;
            $cart->delete();

            CartMaster::UpdateSummation($master_id);

            return $cart;
        }
        return false;
    }

    function cartSaveForLater()
    {
        $carts = Cart::whereNotNull('id')
            ->where('user_id', Auth::id())
            ->where('payment_status', '!=', 68)
            ->where('trashed_status', 2)
            ->where('coin_id', session('coinID'))
            ->with([
                'course',
                'course.upload',
                'trainingOption.TrainingOptionFeature.feature',
                'trainingOption.type',
                'cartFeatures',
            ])
            ->orderBy('id', 'desc')
            ->get();
        return $carts;
    }

    function moveToLater()
    {
        $cart = Cart::where('id', request()->cart_id)->first();
        $cart->update(['trashed_status' => 2]);
        $cart_master_total = CartMaster::where('user_id', auth()->id())->where('payment_status', 63)->first();
        CartMaster::updateOrCreate([
            'user_id' => auth()->id(),
            'payment_status' => 63,
        ], [
            'invoice_amount' => NumberFormat($cart_master_total->invoice_amount - $cart->total_after_vat),
            'total' => NumberFormat($cart_master_total->total - $cart->total),
            'vat_value' => NumberFormat($cart_master_total->vat_value - $cart->vat_value),
            'total_after_vat' => NumberFormat($cart_master_total->total_after_vat - $cart->total_after_vat),
        ]);
        return 'done';
    }

    function moveToCart()
    {
        $coin_id = GetCoinId();
        $coin_price = GetCoinPrice();

        $cart = Cart::where('id', request()->cart_id)->first();
        $cart->update(['trashed_status' => null]);

        $cart_master_total = CartMaster::where('user_id', auth()->id())->where('payment_status', 63)->first();

        $invoice_amount = $total = $vat_value = $total_after_vat = 0;
        if ($cart_master_total) {
            $invoice_amount = $cart_master_total->invoice_amount;
            $total = $cart_master_total->total;
            $vat_value = $cart_master_total->vat_value;
            $total_after_vat = $cart_master_total->total_after_vat;
        }

        $user_token = rand(000000, 999999) . time();

        if (Cookie::get('user_token')) {
            $user_token = Cookie::get('user_token');
        }

        $cartMaster = CartMaster::updateOrCreate([
            'user_id' => auth()->id(),
            'payment_status' => 63,
        ], [
            'invoice_amount' => NumberFormat($invoice_amount + $cart->total_after_vat),
            'total' => NumberFormat($total + $cart->total),
            'vat_value' => NumberFormat($vat_value + $cart->vat_value),
            'total_after_vat' => NumberFormat($total_after_vat + $cart->total_after_vat),
            'invoice_number' => $cart_master_total->invoice_number ?? date("His") . rand(1234, 9632),
            'type_id' => 374,
            'coin_id' => $coin_id,
            'coin_price' => $coin_price,
            'user_token' => $user_token,
        ]);
        $cart->update([
            'master_id' => $cartMaster->id,
        ]);
        return 'done';
    }

    function promoCodeCart()
    {
        $cart_id = request()->cart_id??18151;
        $promocode = request()->PromoCode??'bakkah-bfebd09aec';

        $cart = Cart::find($cart_id);
        $session_id = $cart->session_id;

        $session = Session::with('trainingOption')->find($session_id);
        $training_option_id = $session->training_option_id;

        $discount_detail = DiscountDetail::SmartPromocode($promocode, $training_option_id, $session_id);

        if ($discount_detail) {

            CartMaster::UpdateTotal($cart, $discount_detail);
            // $cart = Cart::find($cart_id);

            /*$price = $cart->price;
            // calculate new price
            $discount_id = $discount_detail->id;
            $discount = $discount_detail->value;
            $discount_value = $price * $discount_detail->value / 100;
            $new_price = $price - $discount_value;

            $new_total = $new_price + $cart->exam_price + $cart->take2_price + $cart->exam_simulation_price;
            $new_value = $new_total * $cart->vat / 100;
            $new_total_after_vat = $new_total + $new_value;
            */

            // update cart
            // $cart->update([
            //     'discount_id' => $discount_id,
            //     'discount' => $discount,
            //     'discount_value' => $discount_value,
            //     'total' => $new_total,
            //     'total_after_vat' => $new_total_after_vat,
            //     'promo_code' => $promocode
            // ]);

            // CartMaster::UpdateSummation($cart->master_id);

            // $cart = Cart::find($cart_id);
            $CartHelper = new CartHelper();

            $GetCarts = $CartHelper->GetCarts();

            if(is_null($GetCarts)){
                return;
            }
            $carts = $GetCarts->first();
            $CartMaster = CartMaster::where('id', $cart->master_id)->first();
            return [
                'cart'=>$carts,
                'CartMaster'=>$CartMaster,
            ];
        } else {
            return '';
        }
    }
}
