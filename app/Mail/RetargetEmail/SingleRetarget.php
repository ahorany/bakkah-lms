<?php

namespace App\Mail\RetargetEmail;

use App\Models\Training\Cart;
use App\Models\Training\Discount\RetargetDiscount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SingleRetarget extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $retargetDiscount;
    public function __construct($cart, $retargetDiscount)
    {
        $this->cart = $cart;
        $this->retargetDiscount = $retargetDiscount;
    }

    public function build()
    {
        $cart = Cart::with(['course', 'trainingOption.constant', 'userId'])->find($this->cart->id);
        $route = route('education.courses.single', ['slug' => $cart->course->slug, 'method' => $cart->trainingOption->constant->post_type]);
        if($cart->retarget_email_id==361)
        {
            $route = route('epay.cartCheckout', ['cart' => $cart->userId->id, 'master_id' => $cart->master_id??null]);
        }
        // else if($this->cart->register_type!='guest')
        // {
        //     $route = route('education.cart');
        // }

        // else if($this->cart->register_type=='guest')
        // {
        //     $route = route('education.courses.register', ['slug'=>$cart->course->slug, 'session_id'=>$cart->session_id]);
        // }

        $GetRetarget = RetargetDiscount::GetRetarget($this->cart, $this->retargetDiscount, $route);
        if(!is_null($GetRetarget))
        {
            $subject = $GetRetarget['subject'];

            Cart::UpdateReterget($this->cart);

            return $this->subject($subject)
            ->view('emails.courses.retarget_emails.checkout', $GetRetarget);
        }
    }
}
