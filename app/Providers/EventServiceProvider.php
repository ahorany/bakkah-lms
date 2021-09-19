<?php

namespace App\Providers;

use App\Events\NewPrepareRetargetDiscountRegisterTraineeEvent;
use App\Events\NewRetargetDiscountRegisterTraineeEvent;
use App\Events\NewTraineeHasRegisteredEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MailChimpEvent::class => [
            \App\Listeners\MailChimpListener::class,
        ],
        NewPrepareRetargetDiscountRegisterTraineeEvent::class => [
            \App\Listeners\PrepareRetargetDiscountRegisterTraineeListener::class,
        ],
        NewRetargetDiscountRegisterTraineeEvent::class => [
            \App\Listeners\RetargetDiscountRegisterTraineeListener::class,
        ],
        NewTraineeHasRegisteredEvent::class => [
            \App\Listeners\LMSRegisterTraineeListener::class,
        ],
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
