<?php

namespace App\Mail\RetargetEmail;

use App\Models\Training\Discount\DiscountDetail;
use App\Models\Training\Discount\RetargetDiscount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutRetarget extends Mailable
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
        $GetRetarget = RetargetDiscount::GetRetarget($this->cart, $this->retargetDiscount);
        $subject = $GetRetarget['subject'];

        return $this->subject($subject)
        ->view('emails.courses.retarget_emails.checkout', $GetRetarget);
    }
}
