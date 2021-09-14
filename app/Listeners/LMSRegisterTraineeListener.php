<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Controllers\Front\Education\LMSController;
use App\Models\Training\Cart;

class LMSRegisterTraineeListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        sleep(10);

        Cart::where('id', $event->cart->id)->update(['status_id'=>51]);

        $LMSController = new LMSController();
        $LMSController->run($event->cart);
    }
}
