<?php

namespace App\Listeners;

use App\Mail\RetargetEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
// use App\Events\NewRetargetDiscountRegisterTraineeEvent;
use App\Events\NewPrepareRetargetDiscountRegisterTraineeEvent;
use App\Models\Training\Cart;
use App\Models\Training\Discount\Discount;
use Illuminate\Database\Eloquent\Builder;

class RetargetDiscountRegisterTraineeListener implements ShouldQueue
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

            // if($cart->retargetDiscount->value > $cart->discount)
            {
                $retarget_email_id = [
                    328=>329,
                    329=>330,
                    330=>339,
                ][$cart->retarget_email_id];

                $retarget_discount = $cart->retargetDiscount->value??0;

                $_run = true;
                if($cart->discount > $retarget_discount){
                    $_run = Discount::IsActive($cart);
                }

                if($_run){
                    Mail::to($cart->userId->email)
                    ->send(new RetargetEmail($cart));

                    if($cart->discount > $retarget_discount){
                        $retarget_discount = $cart->discount;
                        $retarget_discount_value = $cart->discount_value;

                        Cart::where('id', $cart->id)
                        ->update([
                            'retarget_email_id'=>$retarget_email_id,
                            'retarget_email_date'=>now(),
                            'retarget_discount'=>$retarget_discount,
                            'retarget_discount_value'=>$retarget_discount_value,
                        ]);
                    }
                    else
                    {
                        $retarget_discount_value = $retarget_discount * $cart->price / 100;
                        $total = $cart->price - NumberFormat($retarget_discount_value);
                        $total += $cart->exam_price + $cart->take2_price;
                        $vat_value = $total * $cart->vat / 100;
                        $total_after_vat = $total + NumberFormat($vat_value);

                        Cart::where('id', $cart->id)
                        ->update([
                            'retarget_email_id'=>$retarget_email_id,
                            'retarget_email_date'=>now(),
                            'retarget_discount'=>$retarget_discount,
                            'retarget_discount_value'=>$retarget_discount_value,
                            'total'=>$total,
                            'vat_value'=>$vat_value,
                            'total_after_vat'=>$total_after_vat,
                        ]);
                    }

                    $cart = Cart::find($cart->id);
                    event(new NewPrepareRetargetDiscountRegisterTraineeEvent($cart));
                }
            }
        }
    }
}
