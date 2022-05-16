<?php

namespace App\Mail;

use App\Models\Training\Course;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TraineeMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($username, $course)
    {
        $this->username = $username;
        $this->course      = $course;
    }

    public function build()
    {
        return $this->view('training.mails.trainee-mail', [
            'username'     => $this->username,
            'course'       => $this->course,
        ]);
    }
}
