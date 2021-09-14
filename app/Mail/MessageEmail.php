<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function build()
    {
        // $subject = 'Ramadan Offers End Today!';
        $subject = 'Zoom Email to Design thinking and creativity for innovation Learning Session';

        return $this->subject($subject)
        ->view('emails.message');
    }
}
