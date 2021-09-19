<?php

namespace App\Jobs;

use App\Mail\RetargetEmail;
use App\Models\Training\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Training\Discount\RetargetDiscount;

// use App\Events\NewPrepareRetargetDiscountRegisterTraineeEvent;

class RetargetDiscountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $cart_id;
    public $retarget_discount;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cart_id, $retarget_discount)
    {
        $this->cart_id = $cart_id;
        $this->retarget_discount = $retarget_discount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cart = Cart::find($this->cart_id);
        $retarget_email_id__old = $cart->retarget_email_id;

        $payment_status = isset($cart->payment) ? $cart->payment->payment_status : 63;

        if($payment_status==68){
            Cart::where('id', $cart->id)
            ->update([
                'status_id'=>51,
            ]);
        }
        else if($retarget_email_id__old == 339){

            if($cart->discount > $this->retarget_discount){
                Cart::where('id', $cart->id)
                ->update([
                    'status_id'=>340,
                    'retarget_email_id'=>340,
                    'retarget_email_date'=>now(),
                ]);
            }
            else{
                $retarget_discount_value = 0;
                $total = $cart->price;
                $total += $cart->exam_price + $cart->take2_price;
                $vat_value = $total * $cart->vat / 100;
                $total_after_vat = $total + NumberFormat($vat_value);
                Cart::where('id', $cart->id)
                ->update([
                    'status_id'=>340,
                    'retarget_email_id'=>340,
                    'retarget_email_date'=>now(),
                    'retarget_discount'=>$this->retarget_discount,
                    'retarget_discount_value'=>$retarget_discount_value,
                    'total'=>$total,
                    'vat_value'=>$vat_value,
                    'total_after_vat'=>$total_after_vat,
                ]);
            }
        }
        else if(($payment_status==63 && $cart->total > 0) && $retarget_email_id__old <= 339){

            // if($cart->retargetDiscount->value > $cart->discount)
            {
                $retarget_email_id = [
                    328=>329,
                    329=>330,
                    330=>339,
                ][$retarget_email_id__old];
                // $retarget_discount = $cart->retargetDiscount->value??0;

                // $_run = true;
                // if($cart->discount > $retarget_discount){
                //     $_run = Discount::IsActive($cart);
                // }

                // if($_run)
                {
                    if($cart->discount > $this->retarget_discount){
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
                        $retarget_discount_value = $this->retarget_discount * $cart->price / 100;
                        $total = $cart->price - NumberFormat($retarget_discount_value);
                        $total += $cart->exam_price + $cart->take2_price;
                        $vat_value = $total * $cart->vat / 100;
                        $total_after_vat = $total + NumberFormat($vat_value);

                        Cart::where('id', $cart->id)
                        ->update([
                            'retarget_email_id'=>$retarget_email_id,
                            'retarget_email_date'=>now(),
                            'retarget_discount'=>$this->retarget_discount,
                            'retarget_discount_value'=>$retarget_discount_value,
                            'total'=>$total,
                            'vat_value'=>$vat_value,
                            'total_after_vat'=>$total_after_vat,
                        ]);
                    }

                    if($retarget_email_id < 340)
                    {
                        $cart = Cart::find($cart->id);
                        Mail::to($cart->userId->email)
                        ->send(new RetargetEmail($cart, $retarget_email_id__old));
                    }
                    RetargetDiscount::DispatchJob($this->cart_id, $retarget_email_id);
                }
            }
        }
        // else if($retarget_email_id__old == 339){
        //     Cart::where('id', $cart->id)
        //     ->update([
        //         'status_id'=>340,
        //         'retarget_email_id'=>340,
        //     ]);
        // }
    }
}
