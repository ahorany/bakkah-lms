<?php

namespace App\Jobs\Retarget;

use App\Helpers\ControlDiscountTrait;
use App\Helpers\RedirectRegisterPath;
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

class DiscountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use ControlDiscountTrait;

    public $cart_id;
    public $cart;

    public function __construct($cart_id)
    {
        $this->cart_id = $cart_id;
    }

    public function handle()
    {
        $this->cart = Cart::with(['course', 'trainingOption.constant', 'userId'])->find($this->cart_id);
        if(!is_null($this->cart))
        {
            if($this->cart->retarget_email_id < 364)
            {
                $Redirect = new RedirectRegisterPath($this->cart_id);
                $getFunction = $Redirect->getFunction();
                $Redirect->$getFunction();
                // dump($getFunction);
                // dump($Redirect->$getFunction());
                // $this->$getFunction();
                // $this->single();
            }

            $redirect = $this->GetRetarget();
            // $this->UpdateRetargetCart($redirect);
            if(isset($redirect->current_retarget_email_id)){
                $this->DispatchJob($redirect->current_retarget_email_id);
            }
        }
    }

    public function checkoutWithSameDiscount()
    {
        echo 'bbbbbbbbbbb';
        //redirect to checkout page
        // Mail::to($this->cart->userId->email)
        // ->send(new CheckoutRetarget($this->cart));//, $this->discount
    }

    public function checkout()
    {
        // //redirect to checkout page
        // Mail::to($this->cart->userId->email)
        // ->send(new CheckoutRetarget($this->cart));//, $this->discount
    }

    public function single()
    {
        // redirect to single page
        // Mail::to($this->cart->userId->email)
        // ->send(new SingleRetarget($this->cart));
    }

    public function retarget()
    {
        //redirect to register page
        // $redirect = $this->GetRetarget();
        // if(!is_null($redirect))
        // {
        //     $value = $redirect->value;
        //     // Send Email
        //     Mail::to($this->cart->userId->email)
        //     ->queue(new RegisterRetarget($this->cart, $value));
        // }
    }

    private function GetRetarget()
    {
        return RetargetDiscount::where('current_retarget_email_id', $this->cart->retarget_email_id)->first();
    }

    // private function UpdateRetargetCart($redirect)
    // {
    //     $retarget_discount_value = ($this->cart->price * $redirect->value) / 100;
    //     $this->cart = Cart::where('id', $this->cart_id)->update([
    //         'retarget_email_id'=>$redirect->retarget_email_id,
    //         'retarget_email_date'=>DateTimeNow(),
    //         'retarget_discount'=>$redirect->value,
    //         'retarget_discount_value'=>$retarget_discount_value,
    //     ]);
    // }

    private function DispatchJob($current_retarget_email_id){
        // $cart = Cart::find($cart->id);
        // if($cart->retarget_discount==1)
        {
            // if($user->id!=1 && $user->id!=5390)
            {
                RetargetDiscount::DispatchJob($this->cart_id, $current_retarget_email_id);
            }
        }
    }
}
