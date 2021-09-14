<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Helpers\Mailchimp;

class MailChimpListener implements ShouldQueue
{
    public function handle($event)
    {
        // Add the user into the MailChimp list members
        $Mailchimp = new Mailchimp;
        $Mailchimp->sync($event->user, null, $event->LNAME);
    }
}
