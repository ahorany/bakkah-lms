<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RetargetCampainEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;

    public function __construct()//$cart
    {
        // $this->cart = $cart;
    }

    public function build()
    {
        // $subject = 'Your LAST CHANCE to benefit from Jan. discounts!';
        $subject = 'Roadmap to being a professional in supply chain Webinar';

        return $this->subject($subject)
        ->view('emails.courses.retarget_emails.campain', [
            // 'cart'=>$this->cart,
        ]);
        // return $this->view('view.name');
    }
}
