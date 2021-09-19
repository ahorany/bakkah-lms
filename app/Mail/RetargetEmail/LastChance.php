<?php

namespace App\Mail\RetargetEmail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LastChance extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $retarget_email_id;

    public function __construct($cart, $retarget_email_id)
    {
        $this->cart = $cart;
        $this->retarget_email_id = $retarget_email_id;
    }

    public function build()
    {
        // $subject = [
        //     341=>'Congratulations! You are one step closer to take the course',
        // ][$this->retarget_email_id];
        $subject = 'Congratulations! You are one step closer to take the course';

        return $this->subject($subject)
            ->view('emails.courses.retarget_emails.last-chance', [
                'cart'=>$this->cart,
            ]);
        // return $this->view('view.name');
    }
}
