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

    public function __construct($user_id , $password)
    {
        $this->user_id = $user_id;
        $this->password = $password;
    }

    public function build()
    {
        $user = User::where('id',$this->user_id)->first();
        return $this->view('training.mails.user-mail', [
            'user'=>$user,
            'password'=>$this->password,
        ]);
    }
}
