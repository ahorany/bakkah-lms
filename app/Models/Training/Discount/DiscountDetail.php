<?php

namespace App\Models\Training\Discount;

use App\Constant;
use Illuminate\Database\Eloquent\Model;
use App\Models\Training\Course;
use App\Models\Training\Session;
use App\Models\Training\TrainingOption;
use App\Traits\TrashTrait;
use Carbon\Carbon;

class DiscountDetail extends Model
{
    use TrashTrait;

    protected $guarded = [];

    public function course() {
        return $this->hasOne(Course::class);
    }

    public function trainingOption() {
        return $this->hasOne(TrainingOption::class, 'id', 'training_option_id');
    }

    // remove it
    public function courseList() {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }

    public function session(){
        return $this->hasOne(Session::class, 'id', 'session_id');
    }

    public function discount() {
        return $this->belongsTo(Discount::class, 'master_id');
    }

    public function discountType() {
        return $this->belongsTo(Constant::class, 'training_option_type_id');
    }

    public function getDiscountFromAttribute(){
        if(is_null($this->date_from)){
            return null;
        }
        $date = Carbon::parse($this->date_from);
        return $date->isoFormat('D MMM Y');
    }

    public function getDiscountToAttribute(){
        if(is_null($this->date_to)){
            return null;
        }
        $date = Carbon::parse($this->date_to);
        return $date->isoFormat('D MMM Y');
    }

    public function getDiscountIntervalAttribute(){
        if(is_null($this->discount_from)){
            return null;
        }
        return $this->discount_from.' - '.$this->discount_to;
    }

    public static function GetByPromocode($promocode, $training_option_id, $session_id=null)
    {
        $discount_detail = self::join('discounts', 'discounts.id', 'discount_details.master_id')
            ->where('discounts.code', $promocode)
            ->where('discount_details.training_option_id', $training_option_id)
            ->where('discounts.end_date', '>=', Carbon::now()->format('Y-m-d H:i:s'))
            // ->where('date_to', '>=', Carbon::now()->format('Y-m-d H:i:s'))
            ;
            // dd($discount_detail->select('discount_details.id', 'discount_details.value')->first());
        if(!is_null($session_id)){
            // $discount_detail = $discount_detail->where('discounts.post_type', 'discount');
            $discount_detail = $discount_detail->where('session_id', $session_id);
        }
        // else{
        //     // $discount_detail = $discount_detail->where('discounts.post_type', 'retarget_discounts');
        // }
        $discount_detail = $discount_detail->select('discount_details.id', 'discount_details.value')
            // ->toSql()
            ->first()
            ;
        return $discount_detail;
    }

    public static function SmartPromocode($promocode, $training_option_id, $session_id){

        $discount_detail = self::GetByPromocode($promocode, $training_option_id, $session_id);
        if(is_null($discount_detail))
        {
            $discount_detail = self::GetByPromocode($promocode, $training_option_id, null);
        }
        return $discount_detail;
    }
}
