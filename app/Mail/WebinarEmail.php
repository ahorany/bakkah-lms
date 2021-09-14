<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebinarEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $WebinarsRegistration;

    public function __construct($WebinarsRegistration)
    {
        $this->WebinarsRegistration = $WebinarsRegistration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $session_time_from = \Carbon\Carbon::parse($this->WebinarsRegistration->webinar->session_start_time)->format('D d M, Y H:iA');
        $session_time_to = \Carbon\Carbon::parse($this->WebinarsRegistration->webinar->session_end_time)->format('H:iA');

        return $this->markdown('emails.courses.webinar_registration', [
            'WebinarsRegistration'=>$this->WebinarsRegistration,
            'session_time_from'=>$session_time_from,
            'session_time_to'=>$session_time_to,
        ]);
    }
}
