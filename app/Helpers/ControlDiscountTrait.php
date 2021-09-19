<?php

namespace App\Helpers;

use App\Helpers\Models\Training\SessionHelper;
use App\Models\Training\Cart;
use DateTime;

trait ControlDiscountTrait
{
    public $cart_id;
    public $cart;
    // public $discount;
    public function __construct($cart_id)
    {
        $this->cart_id = $cart_id;
        $this->cart = null;
    }

    public function GetCart()
    {
        $this->cart = Cart::with('course')->find($this->cart_id);
        return $this->cart;
    }

    public function getFunction()
    {
        $cart = $this->GetCart();
        if($cart->payment_status==68){
            return 'single';//Payment Completed
        }

        // dump($cart->session_id);
        $SessionHelper = new SessionHelper;
        $session = $SessionHelper->SessionsForRetarget()
        ->where('session_id', $cart->session_id)
        ->first();
        if(!is_null($session))
        {
            $discount_value = !is_null($session->discount_value)?round($session->discount_value, 1):0;

            if(!is_null($cart->discount_id))//has discount
            {
                if($cart->discount_id==$session->discount_id)
                {
                    // dump(round($cart->discount, 1).'==>'.round($session->discount_value, 1));
                    if(round($cart->discount, 1)==round($session->discount_value, 1)){
                        // dump(':same');
                        return 'checkoutWithSameDiscount';
                    }
                    return 'retargetWithSameDiscount';//I can remove it
                }
            }
            else
            {
                if($discount_value!=0)
                {
                    return 'single';
                }
                else
                {
                    return 'retarget';
                }
            }
            return 'single';
        }
        //We don't have a discount, go to single page
        return 'single';
    }
}
