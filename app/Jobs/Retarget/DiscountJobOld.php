<?php

namespace App\Jobs\Retarget;

use App\Helpers\Jobs\Retarget\ControlDiscountJob;
use App\Helpers\Models\Training\SessionHelper;
use App\Mail\RetargetEmail;
use App\Mail\RetargetEmail\CheckoutRetarget;
use App\Mail\RetargetEmail\RegisterRetarget;
use App\Mail\RetargetEmail\SingleRetarget;
use App\Models\Training\Cart;
use App\Models\Training\Discount\RetargetDiscount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DiscountJobOld implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $cart_id;
    public $cart;
    public $retarget;
    public $discount;
    public $ControlDiscountJob;

    public function __construct($cart_id)
    {
        $this->cart_id = $cart_id;
        $this->discount = null;
        $this->ControlDiscountJob = new ControlDiscountJob($this->cart_id);
    }

    public function handle()
    {
        $ControlDiscountJob = $this->ControlDiscountJob;

        $this->cart = $ControlDiscountJob->GetCart();

        $this->retarget = $this->GetRetarget();

        // $session = $ControlDiscountJob->GetSession();
        $SessionHelper = new SessionHelper;
        $session = $SessionHelper->Sessions()->where('session_id', $this->cart->session_id)->first();
        if(!is_null($session))
        {
            $this->discount = $ControlDiscountJob->GetDiscount();
            //We have a discount (or active)
            if(!is_null($this->discount))
            {
                // Cart::where('id', $this->cart->id)->update(['promo_code'=>$this->discount->id]);
                // $function = $ControlDiscountJob->DiscountType($this->discount->discountType->slug);
                // $this->$function();
            }
            else
            {
                //We don't have a discount
                if($this->cart->discount==0)
                {
                    $value = $this->retarget->value;
                    Mail::to($this->cart->userId->email)
                    ->send(new RegisterRetarget($this->cart, $value));
                }

                // $this->RunControlJob();

                // Cart::where('id', $this->cart->id)->update([
                //     'promo_code'=>'123',
                // ]);
            }
        }
        //else
        //InActive: Go to single page
    }

    private function SendRetargetEmail()
    {
        if(!is_null($this->cart))
        {
            $ControlDiscountJob = $this->ControlDiscountJob;

            $this->discount = $ControlDiscountJob->GetDiscount();

            if($this->cart->discount==0)// 0, 15
            {
                $value = $this->retarget->value;

                //redirect to register page
                Mail::to($this->cart->userId->email)
                ->send(new RegisterRetarget($this->cart, $value));
            }
            else if($this->discount->value == $this->cart->discount)
            {
                //redirect to checkout page
                Mail::to($this->cart->userId->email)
                ->send(new CheckoutRetarget($this->cart, $this->discount));
            }
            else
            {
                //redirect to single page
                Mail::to($this->cart->userId->email)
                ->send(new SingleRetarget($this->cart));
            }
            // $this->UpdateRetargetCart();
        }
    }

    private function GetRetarget()
    {
        return RetargetDiscount::where('current_retarget_email_id', $this->cart->retarget_email_id)->first();
    }

    private function UpdateRetargetCart()
    {
        $this->cart = Cart::where('id', $this->cart_id)->update([
            'retarget_email_id'=>$this->retarget->retarget_email_id,
            'retarget_email_date'=>now(),
        ]);
    }

    private function RunControlJob()
    {
        $this->retarget = $this->GetRetarget();
        if($this->retarget->retarget_email_id!=340){
            $ControlDiscountJob = new ControlDiscountJob($this->cart_id);
            $ControlDiscountJob->RunControlJob();
        }
    }

    //We need discount before start date
    //We are not need anything, just apply the same discount
    //Send DiscountJob
    private function BySession(){

        $this->SendRetargetEmail();

        $this->RunControlJob();
    }

    //We need discount between start date and end date
    //Then Discount is Active
    private function ByDate(){

        $this->SendRetargetEmail();

        $this->RunControlJob();

        // $discount = $this->discount;

        // if($discount->date_from >= DateTimeNow() && $discount->date_to <= DateTimeNow())
        // {
        //     $this->SendRetargetEmail();

        //     $this->RunControlJob();
        // }
    }
}
