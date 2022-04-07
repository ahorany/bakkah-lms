<?php

namespace App\Mail;

use App\Models\Training\Course;
use App\Models\Training\Message;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($message_data , $receive)
    {
        $this->message_data = $message_data;
        $this->receive = $receive ;
    }

    public function build()
    {
        $message_data = $this->message_data;
        $recieve = $this->receive;
        return $this->view('training.mails.message-mail',compact('recieve','message_data'));
    }
}
