<?php

namespace App\Mail;

use App\Models\Training\Course;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($user,$username, $password)
    {
        $this->user     = $user;
        $this->username = $username;
        $this->password = $password;
    }

    public function build()
    {
        return $this->view('training.mails.user-mail', [
            'user'    => $this->user,
            'username'=> $this->username,
            'password'=> $this->password,
        ]);
    }
}
