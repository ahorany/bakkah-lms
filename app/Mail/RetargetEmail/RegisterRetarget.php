<?php

namespace App\Mail\RetargetEmail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterRetarget extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $value;

    public function __construct($cart, $value)
    {
        $this->cart = $cart;
        $this->value = $value;
    }

    public function build()
    {
        $value = $this->value;
        $training_name = $this->cart->trainingOption->en_training_name;

        $subject = [
            361=>'Enroll into '.$training_name.' in just one step',
            362=>'Congratulations! You got a '.$value.'% discount on '.$training_name,
            363=>'Your last chance to get a '.$value.'% discount to attend '.$training_name,
        ][$this->cart->retarget_email_id];

        return $this->subject($subject)
            ->view('emails.courses.retarget_emails.register', [
                'cart'=>$this->cart,
                'training_name'=>$training_name,
                'value'=>$value,
            ]);
        // return $this->view('view.name');
    }
}
