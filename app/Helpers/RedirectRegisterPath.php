<?php

namespace App\Helpers;

use App\Helpers\ControlDiscountTrait;
use App\Mail\RetargetEmail\CheckoutRetarget;
use App\Mail\RetargetEmail\SingleRetarget;
use App\Models\Training\Cart;
use App\Models\Training\Discount\DiscountDetail;
use App\Models\Training\Discount\RetargetDiscount;
use Illuminate\Support\Facades\Mail;

class RedirectRegisterPath {

    use ControlDiscountTrait;
    public $cart_id;

    public function __construct($cart_id)
    {
        $this->cart_id = $cart_id;
    }

    public function checkoutWithSameDiscount(){

        // echo 'checkoutWithSameDiscount';
        // $cart = Cart::find(14486);//361
        $cart = Cart::with(['course', 'trainingOption.constant', 'userId'])->find($this->cart_id);
        $retargetDiscount = RetargetDiscount::where('current_retarget_email_id', $cart->retarget_email_id??361)
        ->where('has_discount', 1)
        ->first();

        Mail::to($cart->userId->email)
        ->send(new SingleRetarget($cart, $retargetDiscount));
        // ->send(new CheckoutRetarget($cart, $retargetDiscount));
    }

    public function retargetWithSameDiscount(){
        echo 'retargetWithSameDiscount';
    }

    public function register(){
        echo 'register';
    }

    public function checkout(){

        // echo 'checkout';
        $cart = Cart::with(['course', 'trainingOption.constant', 'userId'])->find($this->cart_id);
        $retargetDiscount = RetargetDiscount::where('current_retarget_email_id', $cart->retarget_email_id??361)
        ->where('has_discount', 0)
        ->first();

        if($this->SendRetargetEmailId($cart)){

            Mail::to($cart->userId->email)
            ->send(new SingleRetarget($cart, $retargetDiscount));
        }
        // ->send(new CheckoutRetarget($cart, $retargetDiscount));
    }

    public function single(){

        $cart = Cart::with(['course', 'trainingOption.constant', 'userId'])->find($this->cart_id);
        $retargetDiscount = RetargetDiscount::where('current_retarget_email_id', $cart->retarget_email_id??361)
        ->where('has_discount', 1)
        ->first();

        Mail::to($cart->userId->email)
        ->send(new SingleRetarget($cart, $retargetDiscount));
    }

    public function retarget(){

        // echo 'retarget';
        $cart = Cart::with(['course', 'trainingOption.constant', 'userId'])->find($this->cart_id);
        // dump($cart);
        $retargetDiscount = RetargetDiscount::where('current_retarget_email_id', $cart->retarget_email_id??361)
        ->where('has_discount', 0)
        ->first();

        if($this->SendRetargetEmailId($cart)){

            Mail::to($cart->userId->email)
            ->send(new SingleRetarget($cart, $retargetDiscount));
        }
    }

    private function SendRetargetEmailId($cart){

        $retarget_email_id = $cart->retarget_email_id;
        if($retarget_email_id==362 || $retarget_email_id==363){
            $discountDetail = DiscountDetail::where('course_id', $cart->course_id)
            ->whereNull('session_id')
            ->whereNotNull('training_option_id')
            ->with(['discount'=>function($query)use($retarget_email_id){
                $query->where('post_type', 'retarget_discounts')
                ->where('type_id', $retarget_email_id);
            }])->first();
            if(is_null($discountDetail)){
                return false;
            }
        }
        return true;
    }

    // public $cart_id;
    // public $cart;
    // public $discount;
    // public function __construct($cart_id)
    // {
    //     $this->cart_id = $cart_id;
    // }

    // public function GetCart()
    // {
    //     $this->cart = Cart::with('course')->find($this->cart_id);
    //     return $this->cart;
    // }

    // public function Run()
    // {
    //     $cart = $this->GetCart();

    //     $SessionHelper = new SessionHelper;
    //     $session = $SessionHelper->Sessions()->where('session_id', $cart->session_id)->first();
    //     if(!is_null($session))
    //     {
    //         if(!is_null($session->discount_id) && $session->discount_id != 0)
    //         {
    //             //We have a discount
    //             if($session->discount_id == $cart->discount_id)
    //             {
    //                 if($session->discount_value == $cart->discount && $cart->discount_value != 0)
    //                 {
    //                     //Just we need to send reminder, go to (checkout) page
    //                     //take any value
    //                     // return redirect()->route('epay.checkout', ['cart'=>$cart->id]);
    //                     // dump('same discount');
    //                     return 'checkout';
    //                 }
    //             }
    //             // dump('different discount');
    //         }
    //         return 'register';
    //     }
    //     //We don't have a discount, go to single page
    //     return 'single';
    // }
}
