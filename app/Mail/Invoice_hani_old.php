<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Invoice extends Mailable
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
        // $coin_id = $this->cart->userId->country_id!=58?335:334;
        $coin_id = $this->cart->coin_id??334;
        $invoice = [
            334=>'SAR',
            335=>'USD',
        ][$coin_id];
        // ][$this->cart->coin_id];

        return $this->markdown('emails.courses.invoice.'.$invoice, [
            'user'=>$this->cart->userId,
            'cart'=>$this->cart,
        ]);
    }
}
