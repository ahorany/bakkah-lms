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

class Session extends Model
{
    use ImgTrait;
    use ExcerptTrait, TrashTrait, SeoTrait, UserTrait;
    use DateTrait;

    protected $guarded = ['en_excerpt', 'ar_excerpt'];

    public function getSessionDetailsAttribute(){
        return $this->trainingOption->course->trans_title??null . ' | '. $this->trainingOption->constant->trans_name??null;
    }

    public function getSessionShortDetailsAttribute(){
        return $this->trainingOption->course->en_shortTitle??null . ' | '. $this->trainingOption->constant->en_name??null;
    }

    public function getSessionRegistrationAttribute(){
        return $this->trainingOption->type->trans_name??null . ' | '. $this->published_from??null;
    }

    public function trainingOption(){
        return $this->belongsTo(TrainingOption::class,'training_option_id');
    }

    public function durationType(){
        return $this->belongsTo(Constant::class,'duration_type');
    }

    public function language(){
        return $this->belongsTo(Constant::class,'lang_id');
    }

    public function city(){
        return $this->belongsTo(Constant::class, 'city_id');
    }
    
    // public function trainer(){
    //     return $this->belongsTo(User::class, 'trainer_id');
    // }
     
    public function usersSessions()
    {
        return $this->hasOne(UserSession::class);
    }

    public function getPublishedFromAttribute(){
        $date = Carbon::parse($this->date_from);
        return $date->isoFormat('D MMM Y');
    }

    public function getSessionDateFromAttribute(){  // for Evaluation
        $date = Carbon::parse($this->date_from);
        return $date->isoFormat('D/M/Y');
    }

    public function getPublishedFromForEmailAttribute(){// Print Just English
        $date = Carbon::parse($this->date_from);
        return $date->locale('en')->isoFormat('D MMM Y');
    }

    public function getPublishedToAttribute(){
        $date = Carbon::parse($this->date_to);
        return $date->isoFormat('D MMM Y');
    }

    public function getPublishedSessionAttribute(){
        return $this->published_from.' - '.$this->published_to;
    }

    public function getCertificateFromAttribute(){
        $date = Carbon::parse($this->date_from);
        return $date->isoFormat('MMMM D, Y');
    }

    public function getCertificateToAttribute(){
        $date = Carbon::parse($this->date_to);
        return $date->isoFormat('MMMM D, Y');
    }

    public function getPmpCertificateFromAttribute(){
        $date = Carbon::parse($this->date_from);
        return $date->isoFormat('D MMMM Y');
    }

    public function getPriceByCurrencyAttribute(){
        // $price = $this->price;
        // if(Cache::has('coinID_'.request()->ip())){
        //     if(Cache::get('coinID_'.request()->ip())==335){
        //         $price = $this->price_usd;
        //     }
        // }
        $price = $this->price;
        if(GetCoinId()==335){
            $price = $this->price_usd;
        }
        return !is_null($price)?$price:0;
    }

    public function getExamPriceByCurrencyAttribute(){
        // $price = $this->exam_price;
        // if(Cache::has('coinID_'.request()->ip())){
        //     if(Cache::get('coinID_'.request()->ip())==335){
        //         $price = $this->exam_price_usd;
        //     }
        // }
        $price = $this->exam_price;
        if(GetCoinId()==335){
            $price = $this->exam_price_usd;
        }
        return !is_null($price)?$price:0;
    }

    public function getRetargetDiscountValueAttribute(){
        return $this->retarget_discount * $this->price * 0.01;
    }

    public function GetSubTotal($discount, $exam_is_included=1, $take2_price=0){

        $exam_is_included = old('exam_is_included')??$exam_is_included;

        $discount_value = 0;
        $discount_type = null;
        if(isset($discount) && !is_null($discount)){
            $run_vat = $discount->type->slug!='value'?1:0;
            $discount_type = $discount->type->slug;
            $discount_value = $discount->GetDiscountForProduct($this->price_by_currency, $this->vat, $run_vat);
        }
        // dump($this->price.'===>'.$discount_value);

        $sub_total = 0;
        if($discount_type!='free'){
            $sub_total = $this->price_by_currency - $discount_value;
        }
        // $sub_total -= $retarget_discount_value;

        $exam_price = $exam_is_included==1?$this->exam_price_by_currency:0;
        $sub_total = $sub_total + $exam_price;
        $sub_total += $take2_price;
        return NumberFormat($sub_total);
    }

    public function GetVatValue($discount, $exam_is_included=1, $take2_price=0){
        $vat_value = ($this->vat / 100) * $this->GetSubTotal($discount, $exam_is_included, $take2_price);
        return NumberFormat($vat_value);
    }

    // public function GetTotalValue($discount, $exam_is_included=1, $take2_price=0){
    //     $total = $this->GetSubTotal($discount, $exam_is_included, $take2_price)
    //         + $this->GetVatValue($discount, $exam_is_included, $take2_price);

    //     $discount_value = 0;
    //     if(isset($discount) && !is_null($discount)){
    //         if($discount->type->slug=='value'){
    //             $discount_value = $discount->GetDiscountForProduct($this->price_by_currency, $this->vat, 1);
    //         }
    //     }
    //     $total -= $discount_value;
    //     return NumberFormat($total);
    // }

    public function getOldPriceAttribute(){
        $vat_value = $this->price_by_currency + (($this->vat / 100) * $this->price_by_currency);
        return NumberFormat($vat_value);
    }

    public function ShowPrice($exam_is_included=0){
        $show_price = $this->old_price;
        if($exam_is_included==1){
            $exam_price_by_currency = $this->exam_price_by_currency + (($this->vat / 100) * $this->exam_price_by_currency);
            $show_price += $exam_price_by_currency;
        }
        return NumberFormat($show_price);
    }

    public function scopeGetTotalValue($exam_price=0, $exam_is_included=1, $take2_price=0){
        $total = $this->GetSubTotal11($exam_price, $exam_is_included, $take2_price)
            + $this->GetVatValue11($exam_price, $exam_is_included, $take2_price);

        // $discount_value = 0;
        // if(isset($discount) && !is_null($discount)){
        //     if($discount->type->slug=='value'){
        //         $discount_value = $discount->GetDiscountForProduct($this->price_by_currency, $this->vat, 1);
        //     }
        // }
        // $total -= $discount_value;
        return NumberFormat($total);
    }

    public function GetSubTotal11($exam_price=0, $exam_is_included=1, $take2_price=0){

        $exam_is_included = old('exam_is_included')??$exam_is_included;

        $sub_total = 0;
        // if($discount_type!='free'){
        //     $sub_total = $this->price_by_currency - $discount_value;
        // }
        $exam_price = $exam_is_included==1?$exam_price:0;
        $sub_total = $sub_total + $exam_price;
        $sub_total += $take2_price;
        return NumberFormat($sub_total);
    }

    public function GetVatValue11($exam_price=0, $exam_is_included=1, $take2_price=0){
        $vat_value = ($this->vat / 100) * $this->GetSubTotal11($exam_price, $exam_is_included, $take2_price);
        return NumberFormat($vat_value);
    }

    public function attendants(){
        return $this->hasMany(Attendant::class);
    }

    public static function GetJson($sessions)
    {
        $session_array = [];
        foreach($sessions as $session){

            $trining_short_name = $session->course->trining_short_name??null;
            $trining_option_name = $session->trainingOption->constant->trans_name??null;

            if($session->trainingOption->constant_id??null == 383){
                $trining_city_name = $session->city->trans_name??null;
                $trining_option_name .= ' | '.$trining_city_name;
            }

            $trining_option_name .= ($session->type_id == 370) ? ' | B2B' : '';

            array_push($session_array, [
                'id'=>$session->id,
                'json_title'=>'SID: '.$session->id.' | '.$trining_short_name.' ( '.$session->published_from.'-'.$session->published_to.' )'.' | '.$trining_option_name,
            ]);
        }
        return $session_array;
    }

    public function cart(){
        return $this->hasOne(Cart::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }

    public function attendants_status(){
        return $this->belongsTo(Constant::class,'attendants_status_id');
    }

    public function attendantsStatusUpdated_by(){
        return $this->belongsTo(User::class,'attendants_status_updated_by');
    }


    // public function developer(){
    //     return $this->belongsTo(User::class, 'developer_id');
    // }

    // public function demand(){
    //     return $this->belongsTo(User::class, 'demand_team_id');
    // }

    public function grossMargin(){
        return $this->hasMany(GrossMargin::class);
    }

    public function userSession(){
        return $this->hasMany(UserSession::class);
    }

    
}
