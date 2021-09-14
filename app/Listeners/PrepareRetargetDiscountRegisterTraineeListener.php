<?php

namespace App\Listeners;

use App\Events\NewRetargetDiscountRegisterTraineeEvent;
use App\Models\Training\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PrepareRetargetDiscountRegisterTraineeListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $cart = $event->cart;
        $payment_status = isset($cart->payment) ? $cart->payment->payment_status : 63;

        if(($payment_status==63 && $cart->total > 0) && $cart->retarget_email_id <= 330){

            // dump($cart->retargetDiscount->value.'==>'.$cart->discount);
            // if($cart->retargetDiscount->value > $cart->discount)
            {
                $after_period = $cart->retargetDiscount->after_period??0;
                $after_period = $after_period * 60 * 60;
                if($cart->user_id==1 || $cart->user_id==2){
                    sleep(15);
                }
                else{
                    sleep($after_period);
                }

                event(new NewRetargetDiscountRegisterTraineeEvent($cart));
            }
        }
        else if($cart->retarget_email_id == 339) {

            $after_period = $cart->retargetDiscount->after_period??0;
            $after_period = $after_period * 60 * 60;
            if($cart->user_id==1 || $cart->user_id==2){
                sleep(15);
            }
            else{
                sleep($after_period);
            }
            Cart::where('id', $cart->id)
            ->update([
                'status_id'=>340,
            ]);
        }

        // $sleep = 1 * 5;
        // sleep($sleep);
        // event(new NewRetargetDiscountRegisterTraineeEvent($event->cart));
    }
}
