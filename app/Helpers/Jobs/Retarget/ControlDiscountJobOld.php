<?php

namespace App\Helpers\Jobs\Retarget;

use App\Helpers\Models\Training\SessionHelper;
use App\Jobs\Retarget\DiscountJob;
use App\Models\Training\Cart;
use App\Models\Training\Discount\DiscountDetail;

class ControlDiscountJob {

    public $cart_id;
    public $cart;
    public $discount;
    public function __construct($cart_id)
    {
        $this->cart_id = $cart_id;
    }

    public function GetCart()
    {
        $this->cart = Cart::with('course')->find($this->cart_id);
        return $this->cart;
    }

    public function GetSession()
    {
        $session = $this->cart->session()
        ->whereDate('session_start_time', '>=', DateTimeNowAddHours())
        ->first();
        return $session;
    }

    public function GetDiscount()
    {
        $this->discount = null;
        if(!is_null($this->cart->discount_id))
        {
            $this->discount = DiscountDetail::with('discountType')->find($this->cart->discount_id);
            // $DateTimeNow = DateTimeNow();

            // $this->discount = DiscountDetail::with('discountType')
            // ->whereDate('date_from', '<=', $DateTimeNow)
            // ->whereDate('date_to', '>', $DateTimeNow)
            // ->where('id', $this->cart->discount_id)
            // ->first();

            // if(is_null($this->discount))
            // {
            //     $this->discount = DiscountDetail::with('discountType')
            //     ->whereDate('date_from', '<=', $DateTimeNow)
            //     ->whereDate('date_to', '>', $DateTimeNow)
            //     ->first();
            // }
        }
        return $this->discount;
    }

    // $slug='auto-date' //By Sessions
    // $slug='custom-date' //By date_from and date_to
    public function DiscountType($slug='auto-date')
    {
        return [
            'auto-date' => 'BySession',
            'custom-date' => 'ByDate',
        ][$slug];
    }

    public function Delay(){
        return 1;
    }

    public function RunControlJob(){

        if($this->cart->retarget_discount==1)
        {
            $delay = $this->Delay();

            $job = (new DiscountJob($this->cart->id))
                ->delay(\Carbon\Carbon::now()->addSeconds($delay));
                // ->delay(\Carbon\Carbon::now()->addMinutes($delay));
            dispatch($job);
        }
    }

    //We need discount before start date
    //We are not need anything, just apply the same discount
    //Send DiscountJob
    private function BySession(){

        $this->RunControlJob();
    }

    //We need discount between start date and end date
    //Then Discount is Active
    private function ByDate(){

        $discount = $this->discount;

        if($discount->date_from >= DateTimeNow() && $discount->date_to <= DateTimeNow())
        {
            $this->RunControlJob();
        }
    }

    public function Run()
    {
        $cart = $this->cart;
        $this->GetCart();

        $SessionHelper = new SessionHelper;
        $session = $SessionHelper->Sessions()->where('session_id', $cart->session_id)->first();
        if(!is_null($session))
        {
            if(!is_null($session->discount_id) && $session->discount_id != 0)
            {
                //We have a discount
                if($session->discount_id == $cart->discount_id)
                {
                    if($session->discount_value == $cart->discount && $cart->discount_value != 0)
                    {
                        //Just we need to send reminder, go to (checkout) page
                        //take any value
                        // return redirect()->route('epay.checkout', ['cart'=>$cart->id]);
                        // dump('same discount');
                        return 'checkout';
                    }
                    // else
                    // {
                    //     //Since we have diffrerent discount, go to (register) page
                    //     // $session->discount_value
                    //     // return redirect()->route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id]);
                    //     // dump('different discount');
                    //     // dump($session->discount_value);//take this value
                    //     // dump($cart->discount);
                    //     // return 'register';
                    // }
                }
                // else
                // {
                //     //Since we have diffrerent discount, go to (register) page
                //     // $session->discount_value
                //     // return redirect()->route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id]);
                //     // dump('different discount');
                //     // dump($session->discount_value);//take this value
                //     // dump($cart->discount);
                //     // return 'register';
                // }
                return 'register';
                // dump('We have a discount');
            }
            else
            {
                return 'retarget';
                // $retarget = $this->GetRetarget($cart);
                // dd($retarget);
                // //We don't have a discount
                // //Go to register page
                // dump('We don t have a discount');
            }
        }
        else
        {
            return 'single';
            //We don't have a discount, go to single page
        }
    }
}
