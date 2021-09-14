<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FailSelfLms extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;

    public function __construct($cart)
    {
        $this->cart = $cart;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.courses.fail_self_lms', [
            'cart'=>$this->cart,
        ]);
    }
}
