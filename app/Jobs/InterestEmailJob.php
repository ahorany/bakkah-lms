<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Training\CourseInterest;
use App\Mail\InterestEmail;


class InterestEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        $id = $this->id;

        $CourseInterests = CourseInterest::where('course_id', $id)
        ->where('id', 14)
        ->with(['userId', 'course'])
        ->get();

        // dd($CourseInterests);

        foreach($CourseInterests as $CourseInterest){
            // dump($WebinarsRegistration->userId->email);
            Mail::to($CourseInterest->userId->email)->send(new InterestEmail($CourseInterest));
        }

    }
}

