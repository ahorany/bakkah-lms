<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RetargetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $discount;

    public function __construct($cart, $discount)
    {
        $this->cart = $cart;
        $this->discount = $discount;
    }

    public function build()
    {
        if(isset($this->cart->trainingOption->en_training_name))
        {
            // $value = ($this->cart->retarget_discount > $this->cart->discount)?$this->cart->retarget_discount:$this->cart->discount;
            // $training_name = $this->cart->trainingOption->en_training_name;

            // if($this->cart->retarget_discount > $this->cart->discount){
            //     $subject = [
            //         328=>'Enroll into '.$training_name.' in just one step',
            //         329=>'Congratulations! You got a '.$value.'% discount on '.$training_name,
            //         330=>'Your last chance to get a '.$value.'% discount to attend '.$training_name,
            //     ][$this->cart->retarget_email_id];
            // }else{
            //     $subject = [
            //         328=>'Enroll into '.$training_name.' in just one step',
            //         329=>'It\'s your chance to take '.$training_name,
            //         330=>'Your last chance to seize the offer and Enroll into '.$training_name,
            //     ][$this->cart->retarget_email_id];
            // }
            $value = null;
            if($this->discount->value == $this->cart->discount){
                $value = $this->cart->discount;
            }
            $training_name = $this->cart->trainingOption->en_training_name;

            if(!is_null($value)){
                $subject = [
                    328=>'Enroll into '.$training_name.' in just one step',
                    329=>'Congratulations! You got a '.$value.'% discount on '.$training_name,
                    330=>'Your last chance to get a '.$value.'% discount to attend '.$training_name,
                ][$this->cart->retarget_email_id];
            }else{
                $subject = [
                    328=>'Enroll into '.$training_name.' in just one step',
                    329=>'It\'s your chance to take '.$training_name,
                    330=>'Your last chance to seize the offer and Enroll into '.$training_name,
                ][$this->cart->retarget_email_id];
            }

            return $this->subject($subject)
            ->view('emails.courses.retarget_emails.index', [
                'cart'=>$this->cart,
                'training_name'=>$training_name,
                'value'=>$value,
            ]);
        }
    }
}
