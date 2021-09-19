<?php

namespace App\Models\Training\Discount;

use App\Helpers\Jobs\Retarget\ControlDiscountJob;
use App\Jobs\Retarget\DiscountJob;
use App\Mail\RetargetEmail;
use App\Models\Training\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use App\Jobs\RetargetDiscountJob;
use App\Traits\Json\DetailsTrait;
use App\Traits\JsonTrait;

class RetargetDiscount extends Model
{
    use JsonTrait, DetailsTrait;

    public static function DispatchJob($cart_id, $retarget_email_id=361){
        // dump($retarget_email_id);
        $RetargetDiscount = self::where('current_retarget_email_id', $retarget_email_id)->first();
        // dump($RetargetDiscount);
        if(!is_null($RetargetDiscount))
        {
            if($retarget_email_id < 364)
            {
                $delay = $RetargetDiscount->after_period;
                // if(env('APP_ENV')=='production')
                // {
                //     $delay *= 60;
                // }

                // $job = (new RetargetDiscountJob($cart_id, $RetargetDiscount->value))
                //             // ->delay(\Carbon\Carbon::now()->addRealSecond(5));
                //             ->delay(\Carbon\Carbon::now()->addMinutes($delay));
                // $job = (new ControlDiscountJob($cart_id, $RetargetDiscount->value))

                $job = (new DiscountJob($cart_id))->delay(\Carbon\Carbon::now()->addMinutes($delay));
                // $job = (new DiscountJob($cart_id, $RetargetDiscount->value))->delay(\Carbon\Carbon::now()->addSeconds(10));
                            // ->delay(\Carbon\Carbon::now()->addMinutes($delay));
                dispatch($job);
            }
        }
    }

    public static function GetRetarget($cart, $retargetDiscount, $route)
    {
        if(!isset($retargetDiscount->trans_details)){
            return null;
        }

        $user_id = $cart->user_id;
        $master_id = $cart->master_id;
        $user_name = $cart->userId->trans_name;
        $course_name = $cart->course->trans_title;
        $value = $retargetDiscount->value??0;
        $icon = $retargetDiscount->icon??'retarget_email_discount.png';

        $retarget_email_id = $cart->retarget_email_id;
        $discountDetail = DiscountDetail::where('course_id', $cart->course_id)
        ->whereNull('session_id')
        ->whereNotNull('training_option_id')
        ->with(['discount'=>function($query)use($retarget_email_id){
            $query->where('post_type', 'retarget_discounts')
            ->where('type_id', $retarget_email_id);
        }])->first();

        $subject = str_replace('${course_name}', $course_name, $retargetDiscount->trans_title);

        $dear = str_replace('${user_name}', $user_name, $retargetDiscount->trans_name);
        $details = str_replace('${user_name}', $user_name, $retargetDiscount->trans_details);
        $details = str_replace('${course_name}', $course_name, $details);

        $discount_value = 0;
        $promocode = '';
        if(!is_null($discountDetail)){

            $discount_value = $discountDetail->value;
            $promocode = $discountDetail->discount->code??null;
        }
        $subject = str_replace('${discount_value}', $discount_value, $subject);
        $details = str_replace('${discount_value}', $discount_value, $details);
        $details = str_replace('${promocode}', $promocode, $details);

        // $route = route('epay.'.$routeName, ['cart' => $user_id, 'master_id' => $master_id]);
        return compact('user_id', 'master_id', 'user_name', 'course_name', 'value', 'icon', 'subject'
        , 'dear', 'details', 'route');
    }

    public static function NotCompletedPayments(Cart $cart){

        // $carts = Cart::whereNull('deleted_at')
        // // ->where('session_id', '!=', 0)
        // ->whereHas('payment', function (Builder $query){
        //     $query->where('payment_status', 63); //Not Complete
        // })
        // ->whereHas('session',function (Builder $query){
        //     $query->whereDate('date_from', '>=', now());
        //     // $query->where('retarget_discount', '>', 0);
        // })
        // ->with(['payment', 'session', 'trainingOption', 'retargetDiscount'])
        // ->select('id', 'session_id', 'course_id', 'user_id', 'training_option_id', 'retarget_email_id')
        // ->distinct()
        // ->orderBy('session_id', 'desc')
        // // ->whereIn('id', [1225, 1226])
        // // ->take(1)
        // ->get();

    }
}
