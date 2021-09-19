<?php

namespace App\Models\Training;

use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use Modules\CRM\Entities\GroupInvoiceMaster;
use App\Models\Training\Cart;
use App\Constant;
use App\Models\Admin\Note;
use App\User;
use Illuminate\Support\Facades\DB;

class CartMaster extends Model
{
    use TrashTrait;

    protected $guarded = [];

    public function carts(){
        return $this->hasMany(Cart::class, 'master_id', 'id');
    }

    // public function carts(){

    //     // dd(request()->post_type);

    //     // if group type_id==372
    //     $master_id__field = 'master_id';
    //     if(isset(request()->post_type))
    //     {
    //         if(request()->post_type=='group_invoices'){
    //             $master_id__field = 'group_invoice_id';
    //         }
    //     }

    //     return $this->hasMany(Cart::class, $master_id__field);
    // }

    // public function gcarts(){
    //     return $this->hasMany(Cart::class, 'group_invoice_id');
    // }

    public function payment(){
        return $this->hasOne(Payment::class, 'master_id');
    }

    // public function payments(){
    //     return $this->hasMany(Payment::class, 'master_id');
    // }

    public function rfpGroup(){
        return $this->hasOne(GroupInvoiceMaster::class, 'master_id');
    }

    public function type(){
        return  $this->belongsTo(Constant::class, 'type_id', 'id');
    }

    public function status(){
        return  $this->belongsTo(Constant::class);
    }

    public function paymentStatus(){
        return $this->belongsTo(Constant::class, 'payment_status', 'id');
    }

    public function notes(){
    	return $this->morphMany(Note::class, 'noteable');
    }

    public function userId(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coin(){
        return $this->belongsTo(Constant::class, 'coin_id');
    }

    public static function UpdateSummation($master_id)
    {
        $carts = Cart::with('trainingOption.TrainingOptionFeature')
        ->where('master_id', $master_id)
        ->whereNull('trashed_status')
        ->get();
        // dd($carts);
        foreach($carts as $cart)
        {
            //Dummay
            $training_option_feature_id__exam = null;
            $training_option_feature_id__simulator = 0;
            $training_option_feature_id__exam_take2 = 0;
            $training_option_feature_id__pract_exam_price = 0;
            $training_option_feature_id__book_price = 0;
            foreach($cart->trainingOption->TrainingOptionFeature as $TrainingOptionFeature){
                if($TrainingOptionFeature->feature_id==1){//Exam Voucher

                    if($cart->exam_price != 0) {
                        $vat_value__f = $cart->exam_price * ($cart->vat/100);
                        $total_after_vat__f = $cart->exam_price + $vat_value__f;
                        CartFeature::updateOrCreate([
                            'master_id'=>$cart->id,
                            'training_option_feature_id'=>$TrainingOptionFeature->id,
                        ], [
                            'price'=>$cart->exam_price,
                            'vat'=>$cart->vat,
                            'vat_value'=>$vat_value__f,
                            'total_after_vat'=>$total_after_vat__f,
                        ]);
                        $training_option_feature_id__exam = $TrainingOptionFeature->id;
                    }
                }
                else if($TrainingOptionFeature->feature_id==2){//Simulator
                    $training_option_feature_id__simulator = $TrainingOptionFeature->id;
                }
                else if($TrainingOptionFeature->feature_id==3){//Take2
                    $training_option_feature_id__exam_take2 = $TrainingOptionFeature->id;
                }
                else if($TrainingOptionFeature->feature_id==4){//Practionar
                    $training_option_feature_id__pract_exam_price = $TrainingOptionFeature->id;
                }
                else if($TrainingOptionFeature->feature_id==5){//Book
                    $training_option_feature_id__book_price = $TrainingOptionFeature->id;
                }
            }
            //Dummay

            $feature__price = 0;

            $cartFeature = CartFeature::where('master_id', $cart->id)
            ->select(DB::raw('sum(price) as price, sum(total_after_vat) as total_after_vat'))
            ->first();

            if(!is_null($cartFeature)){
                $feature__price = $cartFeature->price;
                // $feature__total_after_vat = $cartFeature->total_after_vat;
            }
            $cart_total = ($cart->price - $cart->discount_value) + $feature__price;
            $cart__vat_value = $cart_total * ($cart->vat / 100);
            $cart__total_after_vat = $cart_total + $cart__vat_value;

            //////////////////////////////
            $cartFeature__sum_exam_price = CartFeature::where('master_id', $cart->id)
            ->where('training_option_feature_id', $training_option_feature_id__exam)
            ->sum('price');

            $cartFeature__sum_exam_simulator = CartFeature::where('master_id', $cart->id)
            ->where('training_option_feature_id', $training_option_feature_id__simulator)
            ->sum('price');

            $cartFeature__sum_exam_take2 = CartFeature::where('master_id', $cart->id)
            ->where('training_option_feature_id', $training_option_feature_id__exam_take2)
            ->sum('price');

            $cartFeature__sum_pract_exam_price = CartFeature::where('master_id', $cart->id)
            ->where('training_option_feature_id', $training_option_feature_id__pract_exam_price)
            ->sum('price');

            $cartFeature__sum_book_price = CartFeature::where('master_id', $cart->id)
            ->where('training_option_feature_id', $training_option_feature_id__book_price)
            ->sum('price');

            Cart::where('id', $cart->id)->update([
                'exam_price'=>NumberFormat($cartFeature__sum_exam_price),
                'exam_simulation_price'=>NumberFormat($cartFeature__sum_exam_simulator),
                'take2_price'=>NumberFormat($cartFeature__sum_exam_take2),
                'pract_exam_price'=>NumberFormat($cartFeature__sum_pract_exam_price),
                'book_price'=>NumberFormat($cartFeature__sum_book_price),
                'total'=>NumberFormat($cart_total),
                'vat_value'=>NumberFormat($cart__vat_value),
                'total_after_vat'=>NumberFormat($cart__total_after_vat),
            ]);
        }
        $summation_cart = Cart::where('master_id', $master_id)
        ->whereNull('trashed_status')
        ->select(DB::raw('sum(total) as total
        , sum(vat_value) as vat_value
        , sum(total_after_vat) as total_after_vat'))->first();

        CartMaster::where('id', $master_id)->update([
            'total'=>NumberFormat($summation_cart->total),
            'vat_value'=>NumberFormat($summation_cart->vat_value),
            'total_after_vat'=>NumberFormat($summation_cart->total_after_vat),
        ]);

        // if($summation_cart->total_after_vat==0){
        //     CartMaster::where('id', $master_id)->update(['payment_status'=>332]);
        // }
    }

    // public function scopeJoinInsights($query){

    //     $qr = $query->join('constants as countries', function($join){
    //         $join->on('countries.id', '=', 'users.country_id');
    //         $join->where('countries.post_type', 'countries');
    //         if(request()->has('country_id') && request()->country_id!=-1){
    //             $join->where('countries.id', request()->country_id);
    //         }
    //     })
    //     ->leftJoin('cart_masters', function($join){
    //         $join->on('carts.master_id', 'cart_masters.id');
    //         $join->where('cart_masters.type_id', 374);
    //     })
    //     ->leftJoin('payments', function($join){
    //         $join->on('payments.master_id', 'cart_masters.id');
    //         // $join->on('payments.master_id', 'carts.id');
    //         if(request()->has('payment_status') && request()->payment_status != -1 && request()->payment_status != 332){
    //             $join->whereIn('payments.payment_status',[request()->payment_status]);
    //         }
    //         // if(request()->has('register_from') && !is_null(request()->register_from)){
    //         //     $join->whereDate('payments.paid_at', '>=', request()->register_from);
    //         // }
    //         // if(request()->has('register_to') && !is_null(request()->register_to)){
    //         //     $join->whereDate('payments.paid_at', '<=', request()->register_to);
    //         // }
    //     })
    //     ->join('training_options', function($join){
    //         $join->on('training_options.id', 'carts.training_option_id');
    //         if(request()->has('training_option_id') && request()->training_option_id!=-1){
    //             $join->where('training_options.constant_id', request()->training_option_id);
    //         }
    //     })
    //     ->join('constants as methods', function($join){
    //         $join->on('methods.id', '=', 'training_options.constant_id');
    //         $join->where('methods.parent_id', 10);
    //         if(request()->has('training_option_id') && request()->training_option_id!=-1){
    //             $join->where('methods.id', request()->training_option_id);
    //         }
    //     })
    //     ->join('sessions', function($join){
    //         $join->on('sessions.id', 'carts.session_id');
    //         if(request()->has('session_from') && !is_null(request()->session_from)){
    //             $join->whereDate('sessions.date_from', '>=', request()->session_from);
    //         }
    //         if(request()->has('session_to') && !is_null(request()->session_to)){
    //             $join->whereDate('sessions.date_from', '<=', request()->session_to);
    //         }
    //     })
    //     ->join('courses', 'courses.id', 'carts.course_id')
    //     ->join('post_morphs', function($join){
    //         $join->on('courses.id', '=', 'post_morphs.postable_id');
    //         $join->where('postable_type', 'App\Models\Training\Course');
    //         $join->whereNull('table_id');
    //         if(request()->has('category_id') && request()->category_id!=-1){
    //             $join->where('post_morphs.constant_id', request()->category_id);
    //         }
    //     })
    //     ->join('constants', function($join){
    //         $join->on('constants.id', '=', 'post_morphs.constant_id');
    //         $join->where('constants.post_type', 'course');
    //     });

    //     if(request()->has('course_id') && request()->course_id!=-1){
    //         $qr->where('carts.course_id', request()->course_id);
    //     }
    //     if(request()->has('session_id') && request()->session_id!=-1){
    //         $qr->where('carts.session_id', request()->session_id);
    //     }
    //     // if(!request()->has('coin_id_insights') || request()->coin_id_insights==-1){
    //     //     $qr->where('carts.coin_id',334); //SAR
    //     // }
    //     if(request()->has('coin_id_insights') && request()->coin_id_insights!=-1){
    //         $qr->where('carts.coin_id', request()->coin_id_insights);
    //     }else{
    //         $qr->where('carts.coin_id',334); //SAR
    //     }
    //     if(request()->has('register_from') && !is_null(request()->register_from)){
    //         $qr->whereDate('carts.registered_at', '>=', request()->register_from);
    //     }
    //     if(request()->has('register_to') && !is_null(request()->register_to)){
    //         $qr->whereDate('carts.registered_at', '<=', request()->register_to);
    //     }
    //     if(request()->has('register_to')){
    //         if(request()->payment_status==332){
    //             $qr->where('carts.total', 0)
    //             ->where('carts.payment_status', '!=', 332);
    //         }
    //         else if(request()->payment_status==68){
    //             $qr->where('payments.paid_in', '!=', 0);
    //         }
    //     }
    //     return $qr;
    // }

    public static function UpdateTotal($cart, $discount_detail=null){

        $price_currency = ($cart->coin_id==335)?'price_usd':'price';
        $discount_value = $cart->discount_value;

        $args = [];
        if(!is_null($discount_detail)){

            $PromoCode = request()->has('PromoCode')?request()->PromoCode:null;

            $discount_value = $cart->price * $discount_detail->value / 100;

            $args = array_merge($args, [
                'discount_id' => $discount_detail->id,
                'discount' => $discount_detail->value,
                'discount_value' => $discount_value,
                'promo_code'=>$PromoCode,
            ]);
        }

        $CartFeature = CartFeature::where('master_id', $cart->id)
        ->select(DB::raw('sum('.$price_currency.') as sum_prices'))
        ->first();

        $total = ($cart->price - $discount_value) + ($CartFeature->sum_prices??0);
        $vat_value = $total * (VAT / 100);
        $total_after_vat = $total + $vat_value;

        $args = array_merge($args, [
            'total'=>$total,
            'vat_value'=>$vat_value,
            'total_after_vat'=>$total_after_vat,
        ]);

        Cart::where('id', $cart->id)->update($args);

        $cart_sumation = Cart::where('master_id', $cart->master_id)
        ->select(DB::raw('sum(total) as total, sum(vat_value) as vat_value, sum(total_after_vat) as total_after_vat'))
        ->first();

        self::where('id', $cart->master_id)->update([
            'total'=>$cart_sumation->total,
            'vat_value'=>$cart_sumation->vat_value,
            'total_after_vat'=>$cart_sumation->total_after_vat,
        ]);
    }
}
