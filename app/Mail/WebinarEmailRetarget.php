<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebinarEmailRetarget extends Mailable
{
    use Queueable, SerializesModels;

    public $WebinarsRegistration;
    public function __construct($WebinarsRegistration)
    {
        $this->WebinarsRegistration = $WebinarsRegistration;
    }

    public function build()
    {
        // $subject = 'PMP Certification Updates Webinar';
        $subject = $this->WebinarsRegistration->webinar->en_title?? $this->WebinarsRegistration->webinar->trans_title;

        $session_time_from = \Carbon\Carbon::parse($this->WebinarsRegistration->webinar->session_start_time)->format('H:iA');
        $session_time_to = \Carbon\Carbon::parse($this->WebinarsRegistration->webinar->session_end_time)->format('H:iA');

        return $this->subject($subject)
        ->view('emails.courses.webinar_registration_retarget', [
            'WebinarsRegistration'=>$this->WebinarsRegistration,
            'session_time_from'=>$session_time_from,
            'session_time_to'=>$session_time_to,
        ]);
    }
}
