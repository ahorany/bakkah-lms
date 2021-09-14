<?php

namespace App\Models\Training\Discount;

use App\Constant;
use App\Traits\Json\ExcerptTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Discount extends Model
{
    use UserTrait, ExcerptTrait, TrashTrait;
    protected $guarded = ['en_excerpt', 'ar_excerpt'];

    public function discountDetails(){
        return $this->hasMany(DiscountDetail::class, 'master_id', 'id');
    }

    public function type(){
        return $this->belongsTo(Constant::class, 'type_id');
    }

    public function coin(){
        return $this->belongsTo(Constant::class, 'coin_id');
    }

    public function training_option(){
        return $this->belongsTo(Constant::class,'training_option_id');
    }

    public static function GetDiscount($course_id=0, $training_option_id=-1){
        $discount = DB::table('discounts')
            ->join('discount_details', 'discount_details.master_id', '=', 'discounts.id')
            ->select('discounts.id', 'discounts.type_id', 'discounts.value', 'discounts.end_date')
            ->where('discounts.training_option_id', $training_option_id)
            ->where('discount_details.course_id', '=', $course_id)
            ->where('discounts.start_date', '<=', now())
            ->where('discounts.end_date', '>=', now())
            ->whereNull('discounts.deleted_at')
            ->latest('discounts.updated_at')
            ->first();
        return $discount;
    }

    public function GetDiscountForProduct($price=0, $vat=0, $run_vat=1){

        $args = [
            'percentage'=>(($this->value / 100) * $price),
            'free'=>0,
            'value'=>$run_vat==1?$this->value:0,
            // 'value'=>(($this->value * 100) / (100 + $vat)),
        ];
        return $args[$this->type->slug];
    }

    public function GetDiscountValue($vat=0){

        $args = [
            'percentage'=>$this->value,
            'free'=>0,
            // 'value'=>($this->value + (($this->value * 100) / (100 + $vat))),
            'value'=>$this->value,
        ];
        return $args[$this->type->slug];
    }

    public static function IsActive($cart)
    {
        if($cart->discount_id != 0){
            $_run = Discount::where('id', $cart->discount_id)
            ->where('end_date', '>=', now())
            ->first();
        }
        if(!isset($_run)){
            return true;
        }
        return !is_null($_run)?true:false;
    }

    public function countries(){
        return $this->belongsToMany(Constant::class, 'discount_countries', 'discount_id', 'country_id');
    }
    // public static function GetDiscount($course_id=0){
    //     $discount = DB::table('discounts')
    //         ->join('discount_details', 'discount_details.master_id', '=', 'discounts.id')
    //         ->select('discounts.type_id', 'discounts.value', 'discounts.end_date')
    //         ->where('discount_details.course_id', '=', $course_id)
    //         ->whereDate('discounts.start_date', '<=', Carbon::now())
    //         ->whereDate('discounts.end_date', '>=', Carbon::now())
    //         ->whereNull('discounts.deleted_at')
    //         ->latest('discounts.updated_at')->first();
    //     return $discount;
    // }
}
