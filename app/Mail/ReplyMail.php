<?php

namespace App\Mail;

use App\Models\Training\Course;
use App\Models\Training\Message;
use App\Models\Training\Reply;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($reply_id , $recieve_id , $course_id)
    {
        $this->reply_id = $reply_id;
        $this->recieve_id = $recieve_id;
        $this->course_id = $course_id;
    }

    public function build()
    {
        $message_content = Reply::where('id', $this->reply_id)->with(['user'])->first();
        $recieve = User::where('id', $this->recieve_id)->first();
        $course = Course::where('id', $this->course_id)->first();

        return $this->view('training.mails.reply-mail',compact('recieve','message_content','course'));
    }
}
