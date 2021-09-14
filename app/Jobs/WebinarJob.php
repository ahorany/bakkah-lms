<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Training\Webinar;
use App\Models\Training\WebinarsRegistration;
use App\Mail\WebinarEmail;


class WebinarJob implements ShouldQueue
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

        $WebinarsRegistrations = WebinarsRegistration::where('webinar_id', $id)
        // ->where('id', '=', 25)
        // ->where('id', '>=', 25)
        // ->where('id', '<=', 26)
        ->with(['userId', 'webinar'])
        ->get();
        // dd($WebinarsRegistrations);
        // $seconds = 1;
        foreach($WebinarsRegistrations as $WebinarsRegistration){
            // dump($WebinarsRegistration->userId->email);
            Mail::to($WebinarsRegistration->userId->email)->send(new WebinarEmail($WebinarsRegistration));
            // $seconds += 2;
        }

    }
}
