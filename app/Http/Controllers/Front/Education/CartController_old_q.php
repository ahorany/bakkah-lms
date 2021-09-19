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

        $cart_total = $CartHelper->GetTotal($cartMaster->id);
        $CartMasterHelper->updateTotal($cartMaster->id, $cart_total);

        $cart = $CartHelper->GetCart($cart->id);
        return $cart;
    }

/*
    // new method is (addCartItem)
    public function addToCart()
    {
        $course_id = request()->course_id;
        $session_id = request()->session_id;
        $type = request()->type;

        $coin_id = GetCoinId();
        $coin_price = GetCoinPrice();

        $user_token = rand(000000, 999999) . time();

        if (Cookie::get('user_token')) {
            $user_token = Cookie::get('user_token');
        }

        $session = Session::with('trainingOption')->find($session_id);

        $course = Course::find($course_id);
        $SessionHelper = new SessionHelper;

        $course = $SessionHelper->Single($course->slug, ($type == 'training_option') ? true : false)
            ->where('session_id', $session_id)
            ->first();

        $SessionHelper->SetCourse($course);

        $trainingOptionFeatures = TrainingOptionFeature::TrainingOptionFeatures($course->training_option_id);

        $price = $SessionHelper->Price();

        $exam_price = $SessionHelper->ExamPrice();

        $total = $SessionHelper->PriceWithExamPrice();
        // $total = $SessionHelper->PriceAfterDiscount();

        $vat = $SessionHelper->VAT();
        $vat_value = $SessionHelper->VATFortPriceWithExamPrice();
        // $vat_value = $SessionHelper->VATValue();
        $total_after_vat = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();
        // $total_after_vat = $SessionHelper->TotalAfterVAT();

        $discount_id = $course->discount_id;
        $discount = $SessionHelper->Discount() ?? 0;
        $discount_value = $SessionHelper->DiscountValue();

        // $cart_master_old = CartMaster::where('user_token', session('user_token'))
        $cart_master_old = CartMaster::where('user_token', Cookie::get('user_token'))
            ->where('payment_status', 63)
            ->first();

        $total_after_vat_old = $invoice_amount_old = $total_old = $vat_value_old = 0;
        if (Cookie::get('user_token')) {
            // $cart_master_old = CartMaster::where('user_token', session('user_token'))
            $cart_master_old = CartMaster::where('user_token', Cookie::get('user_token'))
                ->where('payment_status', 63)
                ->first();
            // $total_after_vat_old = $invoice_amount_old = $total_old = $vat_value_old = 0;
            // if($cart_master_old) {
            //     $total_after_vat_old = $cart_master_old->total_after_vat;
            //     $invoice_amount_old = $cart_master_old->invoice_amount;
            //     $total_old = $cart_master_old->total;
            //     $vat_value_old = $cart_master_old->vat_value;
            // }
        }

        if (Cookie::get('user_token')) {
            $cartMaster = CartMaster::updateOrCreate([
                // 'user_token' => session('user_token'),
                'user_token' => Cookie::get('user_token'),
                'payment_status' => 63,
            ], [
                'user_id' => auth()->id(),
                'invoice_amount' => NumberFormat($total_after_vat + $invoice_amount_old),
                'total' => NumberFormat($total + $total_old),
                'vat' => NumberFormat($vat),
                'vat_value' => NumberFormat($vat_value + $vat_value_old),
                'total_after_vat' => NumberFormat($total_after_vat + $total_after_vat_old),
                'invoice_number' => date("His") . rand(1234, 9632),
                'type_id' => 374,
                'coin_id' => $coin_id,
                'coin_price' => $coin_price,
            ]);

            $cart = Cart::updateOrCreate(
                [
                    // 'user_token' => session('user_token'),
                    'user_token' => Cookie::get('user_token'),
                    'payment_status' => 63,
                    'session_id' => $session_id
                ],
                [
                    'master_id'          => $cartMaster->id,
                    'session_id'         => $session_id,
                    'status_id'          => 327, //51
                    'user_id'            => auth()->id(),
                    'training_option_id' => $session->training_option_id, //??
                    'course_id'          => $session->trainingOption->course_id, //??
                    'price'              => NumberFormat($price),
                    'exam_price'         => NumberFormat($exam_price),
                    'total'              => NumberFormat($total),
                    'vat'                => NumberFormat($vat),
                    'vat_value'          => NumberFormat($vat_value),
                    'total_after_vat'    => NumberFormat($total_after_vat),
                    'discount_id'        => $discount_id,
                    'discount'           => $discount,
                    'discount_value'     => $discount_value,
                    'invoice_number'     => date("His") . rand(1234, 9632), //??
                    'trying_count'       => 1,
                    'coin_id'            => $coin_id,
                    'coin_price'         => $coin_price,
                    'registered_at'      => DateTimeNow(),
                    'locale'             => app()->getLocale(),
                    'payment_status'     => 63,
                    'user_token'         => $user_token,
                    'register_type'      => 'cart'
                ]
            );
        } else {
            $cartMaster = CartMaster::create([
                'user_token'        => $user_token,
                'payment_status'    => 63,
                'user_id'           => auth()->id(),
                'invoice_amount'    => NumberFormat($total_after_vat + $invoice_amount_old),
                'total'             => NumberFormat($total + $total_old),
                'vat'               => NumberFormat($vat),
                'vat_value'         => NumberFormat($vat_value + $vat_value_old),
                'total_after_vat'   => NumberFormat($total_after_vat + $total_after_vat_old),
                'invoice_number'    => date("His") . rand(1234, 9632),
                'type_id'           => 374,
                'coin_id'           => $coin_id,
                'coin_price'        => $coin_price,
            ]);

            $cart = Cart::create([
                'master_id'          => $cartMaster->id,
                'session_id'         => $session_id,
                'status_id'          => 327, //51
                'user_id'            => auth()->id(),
                'training_option_id' => $session->training_option_id, //??
                'course_id'          => $session->trainingOption->course_id, //??
                'price'              => NumberFormat($price),
                'exam_price'         => NumberFormat($exam_price),
                'total'              => NumberFormat($total),
                'vat'                => NumberFormat($vat),
                'vat_value'          => NumberFormat($vat_value),
                'total_after_vat'    => NumberFormat($total_after_vat),
                'discount_id'        => $discount_id,
                'discount'           => $discount,
                'discount_value'     => $discount_value,
                'invoice_number'     => date("His") . rand(1234, 9632), //??
                'trying_count'       => 1,
                'coin_id'            => $coin_id,
                'coin_price'         => $coin_price,
                'registered_at'      => DateTimeNow(),
                'locale'             => app()->getLocale(),
                'payment_status'     => 63,
                'user_token'         => $user_token,
                'register_type'      => 'cart'
            ]);
        }

        $includeTrainingOptionFeatures = TrainingOptionFeature::where('training_option_id', $cart->training_option_id)
            ->where('is_include', 1)
            ->get();
        //dd($includeTrainingOptionFeatures);
        foreach ($includeTrainingOptionFeatures as $trainingOptionFeature) {

            //$this->deleteCartFeature($cart);
            $this->addCartFeatureItem($cart, $trainingOptionFeature->price, $trainingOptionFeature->id);
            //$this->updateCartFeature($cart);
            //$total_after_vat+=$trainingOptionFeature->total_after_vat;
        }
        //CartMaster::UpdateSumation($cartMaster);
        CartMaster::UpdateSummation($cartMaster->id);

        if (!Cookie::get('user_token')) {
            Cookie::queue('user_token', $user_token, (60 * 24 * 30 * 12));
        }

        return response()->json([
            'cart' => [
                'cart_id' => $cart->id,
                'price' => NumberFormat($total_after_vat),
                'course' => $course,
                'features' => $trainingOptionFeatures
            ]
        ]);
    }
    */

    public function GetCarts()
    {
        // add bandles code
        if (Auth::check()) {
            $carts = Cart::whereNotNull('id')
                ->where('user_id', Auth::id())
                ->where('payment_status', '!=', 68)
                ->where('payment_status', '!=', 332)
                ->whereNull('trashed_status')
                ->whereNull('deleted_at')
                ->where('coin_id', session('coinID'))
                ->whereHas('cartMaster', function (Builder $query) {
                    $query->where('user_id', Auth::id());
                })
                ->with([
                    'course',
                    'course.upload',
                    'trainingOption.TrainingOptionFeature.feature',
                    'trainingOption.type',
                    'cartFeatures',
                    'cartMaster',
                ])
                ->orderBy('id', 'desc')
                ->get();

            return $carts;
        } else {
            // if(session('user_token')) {
            if (Cookie::get('user_token')) {
                $carts = Cart::whereNotNull('id')
                    // ->where('user_token', session('user_token'))
                    ->where('user_token', Cookie::get('user_token'))
                    ->where('payment_status', '!=', 68)
                    ->where('payment_status', '!=', 332)
                    ->whereNull('trashed_status')
                    ->where('coin_id', session('coinID'))
                    ->with([
                        'course',
                        'course.upload',
                        'trainingOption.TrainingOptionFeature.feature',
                        'trainingOption.type',
                        'cartFeatures'
                    ])
                    ->orderBy('id', 'desc')
                    ->get();

                return $carts;
            }
        }
        return null;
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

    //move to RegisterHelper
    //TODO: ahorany
    // public function updateCartFeature($cart)
    // {
    //     $features_sum = CartFeature::where('master_id', $cart->id)->sum('price');
    //     $features_VAT = GetCoinId() == 334 ? ((VAT / 100) * $features_sum) : 0;
    //     $features_sum_after_vat = GetCoinId() == 334 ? $features_VAT + $features_sum : $features_sum;
    //     $cartFeatures = CartFeature::where('master_id', $cart->id)->get();
    //     $exam_price = 0;
    //     $take2_price = 0;
    //     $exam_simulation_price = 0;
    //     foreach ($cartFeatures as $cartFeature) {
    //         if ($cartFeature->trainingOptionFeature->feature_id == 1) {
    //             $exam_price = $cartFeature->price;
    //         } else if ($cartFeature->trainingOptionFeature->feature_id == 2) {
    //             $exam_simulation_price = $cartFeature->price;
    //         } else if ($cartFeature->trainingOptionFeature->feature_id == 3) {
    //             $take2_price = $cartFeature->price;
    //         }
    //     }
    //     $cart = Cart::find($cart->id);
    //     Cart::where('id', $cart->id)->update([
    //         'exam_price' => $exam_price,
    //         'take2_price' => $take2_price,
    //         'exam_simulation_price' => $exam_simulation_price,
    //         'total' => $cart->total + $features_sum,
    //         'vat_value' => $cart->vat_value + $features_VAT,
    //         'total_after_vat' => $cart->total_after_vat + $features_sum_after_vat,
    //     ]);
    // }

    //move to RegisterHelper
    public function addCartFeatureItem($cart, $price, $training_option_feature)
    {
        $vat = 0;
        $vat_value = 0;
        $total_after_vat = $price;
        if (GetCoinId() == 334) {
            $vat = VAT;
            $vat_value = $price * ($vat / 100);
            $total_after_vat = $price + $vat_value;
        }

        CartFeature::create([
            'master_id' => $cart->id,
            'training_option_feature_id' => $training_option_feature,
            'price' => $price,
            'vat' => $vat,
            'vat_value' => $vat_value,
            'total_after_vat' => $total_after_vat
        ]);
    }

    //TODO: ahorany
    // public function deleteCartFeature($cart)
    // {
    //     $features_sum1 = CartFeature::where('master_id', $cart->id)->sum('price');
    //     $features_VAT1 = GetCoinId() == 334 ? ((VAT / 100) * $features_sum1) : 0;
    //     $features_sum_after_vat1 = GetCoinId() == 334 ? $features_VAT1 + $features_sum1 : $features_sum1;
    //     $cart = Cart::find($cart->id);
    //     Cart::where('id', $cart->id)->update([
    //         'total' => $cart->total - $features_sum1,
    //         'vat_value' => $cart->vat_value - $features_VAT1,
    //         'total_after_vat' => $cart->total_after_vat - $features_sum_after_vat1,
    //     ]);
    // }

    function addCartFeature()
    {
        $cart_id = request()->cart_id??18151;
        $cart = Cart::find($cart_id);

        $CartHelper = new CartHelper();
        $CartHelper->AddOrRemoveFeature($cart, request()->feature_id);

        CartMaster::UpdateTotal($cart);

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

        $cart = Cart::find($cart_id);
        $CartMaster = CartMaster::where('id', $cart->master_id)->first();
        return [
            'cart'=>json_decode($cart),
            'CartMaster'=>$CartMaster,
        ];
        // return 'done';
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
