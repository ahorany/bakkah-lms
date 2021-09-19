<?php

namespace App\Mail\RetargetEmail;

use App\Models\Training\Discount\DiscountDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutRetarget__old extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    // public $discount;

    public function __construct($cart)
    {
        $this->cart = $cart;
        // $this->discount = $discount;
    }

    public function build()
    {
        if(isset($this->cart->trainingOption->en_training_name))
        {
            $discount = DiscountDetail::where('master_id', $this->cart->discount_id)->first();

            $value = null;
            if(!is_null($discount)){
                if($discount->value == $this->cart->discount){
                    $value = $this->cart->discount;
                }
            }
            $training_name = $this->cart->trainingOption->en_training_name;

            // $subject = 'Enroll checkout';
            // $subject = 'register';
            $subject = 'The registered '.$training_name.' session has expired';

            if(is_null($value) && $this->cart->retarget_email_id!=328){

                // $subject = 'register';
                $subject = 'The registered '.$training_name.' session has expired';
                return $this->subject($subject)
                ->view('emails.courses.retarget_emails.register', [
                    'cart'=>$this->cart,
                    'training_name'=>$training_name,
                    'value'=>$value,
                ]);

            }else{

                $subject = [
                    328=>'Enroll into '.$training_name.' in just one step',
                    329=>'Congratulations! You got a '.$value.'% discount on '.$training_name,
                    330=>'Your last chance to get a '.$value.'% discount to attend '.$training_name,
                ][$this->cart->retarget_email_id];

                return $this->subject($subject)
                ->view('emails.courses.retarget_emails.checkout', [
                    'cart'=>$this->cart,
                    'training_name'=>$training_name,
                    'value'=>$value,
                ]);

            }
        }
    }
}
