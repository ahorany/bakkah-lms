<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Training\CourseInterest;
use App\Mail\Mail;

class InterestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $CourseInterest;

    public function __construct($CourseInterest)
    {
        $this->CourseInterest = $CourseInterest;
    }

    public function build()
    {
        // $subject = 'March Offers are Available Now!';
        $subject = 'Claim your %25 discount now!';

        return $this->subject($subject)
        ->view('emails.courses.interest', [
            'CourseInterest'=>$this->CourseInterest,
        ]);

    }
}
