<?php

namespace App\Jobs;

use App\Mail\RetargetEmail\LastChance;
use App\Models\Training\Cart;
use App\Models\Training\Course;
use App\Models\Training\Discount\Discount;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class LastChanceRetargetEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $carts;

    public function __construct($carts)
    {
        $this->carts = $carts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $carts = $this->carts;
        foreach($carts as $cart){

            $cart = Cart::with('trainingOption')->find($cart->id);
            if($cart->retarget_email_id != 352){
                $constant_id = $cart->trainingOption->constant_id??13;
                $discount = Discount::GetDiscount($cart->course_id, $constant_id);
                $discountOBJ = null;
                $discount_rate = 0;
                if(isset($discount->id)){
                    $discountOBJ = Discount::find($discount->id);
                    $discount_rate = $discount->value;
                }

                $course = Course::where('id', $cart->course_id)
                ->with(['trainingOption'=>function($query) use($constant_id){
                    $query->where('constant_id', '=', $constant_id);
                }, 'trainingOption.session'=>function($query){
                    $query->whereDate('date_from', '>=', now());
                    $query->where('session_start_time', '>=', Carbon::now()->subHour(SUBHOUR)->format('Y-m-d H:i:s'));
                }])
                ->first();

                $seconds = 1;
                if(isset($course->trainingOption->session->exam_price)){
                    $discount_value = ($discount_rate/100) * $course->trainingOption->session->price;
                    //where('id', $cart->id)->
                    $cart = Cart::updateOrCreate([
                        'user_id'=>$cart->user_id,
                        'course_id'=>$course->id,
                        'session_id'=>$course->trainingOption->session->id,
                        'training_option_id' => $course->trainingOption->session->training_option_id,//??
                        'status_id'=>327,
                    ],[
                        'price' => NumberFormat($course->trainingOption->session->price),
                        'discount_id' => $discount->id??null,
                        'discount' => NumberFormat($discount_rate),
                        'discount_value' => NumberFormat($discount_value),
                        'exam_price' => NumberFormat($course->trainingOption->session->exam_price),
                        'take2_price' => NumberFormat($cart->take2_price),
                        'total' => $course->trainingOption->session->GetSubTotal($discountOBJ, $course->exam_is_included, $cart->take2_price),
                        'vat' => NumberFormat($course->trainingOption->session->vat),
                        'vat_value' => $course->trainingOption->session->GetVatValue($discountOBJ, $course->exam_is_included, $cart->take2_price),
                        'total_after_vat' => $course->trainingOption->session->GetTotalValue($discountOBJ, $course->exam_is_included, $cart->take2_price),
                        'retarget_email_id'=>352,
                        'retarget_email_date'=>now(),
                        'invoice_number' => date("His").rand(1234, 9632),//??
                        'coin_id'=>1,
                        'coin_price'=>1,
                    ]);
                }
                $when = now()->addSeconds($seconds);
                Mail::to($cart->userId->email)
                    ->later($when, new LastChance($cart, 341));
                // Mail::to($cart->userId->email)
                //         ->send(new LastChance($cart, 341));
                $seconds += 3;
            }
        }
    }
}
