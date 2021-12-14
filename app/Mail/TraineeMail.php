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

    //public $CourseInterest;

    // public function __construct($CourseInterest)
    // {
    //     $this->CourseInterest = $CourseInterest;
    // }

    public function __construct($user_id , $course_id)
    {
        $this->user_id = $user_id;
        $this->course_id = $course_id;
    }

    public function build()
    {
        $user = User::where('id',$this->user_id)->first();
        $course = Course::where('id',$this->course_id)->first();
        return $this->view('training.mails.trainee-mail', [
            'user'=>$user,
            'course'=>$course,
        ]);
    }
}
