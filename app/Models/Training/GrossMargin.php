<?php

namespace App\Models\Training;

use App\Constant;
use App\Traits\DateTrait;
use App\Traits\ImgTrait;
use App\Traits\Json\ExcerptTrait;
use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Cache;

class GrossMargin extends Model
{
    use ImgTrait;
    use ExcerptTrait, TrashTrait, SeoTrait, UserTrait;
    use DateTrait;

    protected $guarded = [];
    protected $table = 'gross_margins';

    public function session(){
        return $this->belongsTo(Session::class);
    }

    

    public static function GetCalculations(GrossMargin $grossMargin){

        if($grossMargin){

            // total_hours
            $total_hours = ($grossMargin->session->duration??1) * ($grossMargin->session->hours_per_day??1);
            
            // on_demand_cost
            $daily_rate_type = 'daily_rate'.$grossMargin->GetTypeCode($grossMargin->session->trainingOption->constant_id??13);
            $daily_rate = $grossMargin->session->demand->profile->$daily_rate_type??0;
            $on_demand_cost = $daily_rate * $total_hours;

            // trainer_cost
            $morning_rate_type = 'morning_rate'.$grossMargin->GetTypeCode($grossMargin->session->trainingOption->constant_id??13);
            $morning_rate = $grossMargin->session->trainer->profile->$morning_rate_type??0;
            $trainer_cost = $morning_rate * $total_hours;

            // trainees_no
            $trainees_no = 0;
            $carts = $grossMargin->session->carts->whereIn('payment_status', [68,317,332,315]);
            // $carts = $grossMargin->session->carts;
            // dd($carts);
            if(isset($carts)){
                $trainees_no = $carts->count();
            }

            // attendants
            $sid = $grossMargin->session->id??0;
            $href = false;
            if($sid > 0 && $trainees_no > 0){
                $href = true;
            }
            // $attendants = htmlspecialchars($attendants);

            // material_cost
            $material_cost_course = $grossMargin->session->trainingOption->course->material_cost??0;
            $material_cost = 0;
            if($trainees_no!=0)
            {
                $material_cost = $material_cost_course * $trainees_no;
            }

            // delivery_cost
            $delivery_cost = ($grossMargin->zoom??0) + ($on_demand_cost??0) + ($trainer_cost??0) + ($material_cost??0);

            // sales_value
            $sales_value=0;
            foreach($carts as $cart){

                $price = $cart->price??0;
                $discount_value = $cart->discount_value??0;

                // Free or Bakkah Employee
                if($cart->payment_status == 332 || $cart->payment_status == 315){
                    $price = 0;
                    $discount_value = 0;
                }

                if($cart->coin_id == 335){
                    $price = ($price) * ($cart->coin_price??0);
                    $discount_value = ($discount_value) * ($cart->coin_price??0);
                }

                $sales_value += $price - $discount_value;
                // $sales_value += ($cart->price??0) - ($cart->discount_value??0);
            }

            // gross_profit
            $gross_profit=0;
            $gross_profit = ($sales_value??0) - ($delivery_cost??0);

            // gross_margin
            $sales_value_new = $sales_value;
            if($sales_value == 0){
                $sales_value_new = 1;
            }
            $gross_margin=0;
            $gross_margin = (($gross_profit??0) / $sales_value_new) * 100;

            // dd($gross_margin);

            return compact('total_hours','on_demand_cost', 'trainer_cost','trainees_no','sid','href','material_cost_course','material_cost','delivery_cost','sales_value','gross_profit','gross_margin');

        }else{
            return null;
        }
    }

    public static function GetCalculations__old(GrossMargin $grossMargin){

        if($grossMargin){

            // total_hours
            $total_hours = ($grossMargin->session->duration??1) * ($grossMargin->session->hours_per_day??1);

            // on_demand_cost
            $daily_rate_type = 'daily_rate'.$grossMargin->GetTypeCode($grossMargin->session->trainingOption->constant_id??13);
            $daily_rate = $grossMargin->session->demand->profile->$daily_rate_type??0;
            $on_demand_cost = $daily_rate * $total_hours;

            // trainer_cost
            $morning_rate_type = 'morning_rate'.$grossMargin->GetTypeCode($grossMargin->session->trainingOption->constant_id??13);
            $morning_rate = $grossMargin->session->trainer->profile->$morning_rate_type??0;
            $trainer_cost = $morning_rate * $total_hours;

            // trainees_no
            $trainees_no = 0;
            $carts = $grossMargin->session->carts->whereIn('payment_status', [68,317,332,315]);
            // $carts = $grossMargin->session->carts;
            // dd($carts);
            if(isset($carts)){
                $trainees_no = $carts->count();
            }

            // attendants
            $sid = $grossMargin->session->id??0;
            $href = false;
            if($sid > 0 && $trainees_no > 0){
                $href = true;
            }
            // $attendants = htmlspecialchars($attendants);

            // material_cost
            $material_cost_course = $grossMargin->session->trainingOption->course->material_cost??0;
            $material_cost = 0;
            if($trainees_no!=0){
                $material_cost = $material_cost_course * $trainees_no;
            }

            // delivery_cost
            $delivery_cost = ($grossMargin->zoom??0) + ($on_demand_cost??0) + ($trainer_cost??0) + ($material_cost??0);

            // sales_value
            $sales_value=0;
            foreach($carts as $cart){
                $sales_value += ($cart->price??0) - ($cart->discount_value??0);
            }

            // gross_profit
            $gross_profit=0;
            $gross_profit = ($sales_value??0) - ($delivery_cost??0);

            // gross_margin
            $sales_value_new = $sales_value;
            if($sales_value == 0){
                $sales_value_new = 1;
            }
            $gross_margin=0;
            $gross_margin = (($gross_profit??0) / $sales_value_new) * 100;

            // dd($gross_margin);

            return compact('total_hours','on_demand_cost', 'trainer_cost','trainees_no','sid','href','material_cost_course','material_cost','delivery_cost','sales_value','gross_profit','gross_margin');

        }else{
            return null;
        }
    }

}
