<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LearnerComplaintEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct()
    {

    }

    public function build()
    {
        return $this->from('jihad@example.com')
                ->view('admin.learn_complaints.email');
    }
}
