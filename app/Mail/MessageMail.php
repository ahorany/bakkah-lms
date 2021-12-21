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

    public function __construct($message_id , $recieve_id)
    {
        $this->message_id = $message_id;
        $this->recieve_id = $recieve_id;
    }

    public function build()
    {
        $message_content = Message::where('id',$this->message_id)->with(['user','course','replies'])->first();
        $recieve = User::where('id',$this->recieve_id)->first();

        return $this->view('training.mails.message-mail',compact('recieve','message_content'));
    }
}
