<?php

namespace App\Mail;

use App\Models\Training\Message;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    //public $CourseInterest;

    // public function __construct($CourseInterest)
    // {
    //     $this->CourseInterest = $CourseInterest;
    // }

    public function __construct($message_id , $recieve_id)
    {
        $this->message_id = $message_id;
        $this->recieve_id = $recieve_id;
    }

    public function build()
    {
        $message = Message::where('id',$this->message_id)->with(['user','course','replies'])->first();
        $recieve = User::where('id',$this->recieve_id)->first();
        return $this->view('training.mails.message-mail', [
            'message'=> $message,
            'recieve'=> $recieve,
        ]);
    }
}
