<?php

namespace App\Mail;

use App\Models\Training\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SelfLms extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $user_id__found;

    public function __construct($cart, $user_id__found)
    {
        $this->cart = $cart;
        $this->user_id__found = $user_id__found;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.courses.self_lms', [
//        return $this->markdown('emails.courses.self_lms', [
            'cart'=>$this->cart,
            'user_id__found'=>$this->user_id__found,
        ]);
    }
}
