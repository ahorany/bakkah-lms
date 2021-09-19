<?php

namespace App\Models\Training;

use App\User;
use App\Constant;
use App\Traits\UserTrait;
use App\Models\Admin\Note;
use App\Traits\TrashTrait;
use App\Models\Training\CartMaster;
use Modules\CRM\Entities\B2bMaster;
use App\Models\Training\CartFeature;
use Illuminate\Database\Eloquent\Model;
use App\Models\Training\Discount\Discount;
use App\Models\Training\Discount\RetargetDiscount;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
    use UserTrait, TrashTrait;

    protected $guarded = [];

    public function session(){
        return $this->belongsTo(Session::class);
    }

    public function trainingOptionOrSession(){
        return $this->belongsTo(TrainingOption::class, 'training_option_id');
    }

    public function payment(){
        return $this->hasOne(Payment::class, 'master_id');
    }

    public function cartFeatures(){
        return $this->hasMany(CartFeature::class, 'master_id');
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function trainingOption(){
        return $this->belongsTo(TrainingOption::class,'training_option_id');
    }

    public function userId(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function discountMethod(){
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function retargetDiscount(){
        return $this->belongsTo(RetargetDiscount::class, 'retarget_email_id', 'retarget_email_id');
    }

    public function coin(){
        return $this->belongsTo(Constant::class, 'coin_id');
    }

    public function paymentStatus(){
        return $this->belongsTo(Constant::class, 'payment_status', 'id');
    }

    public function b2b(){
        return $this->belongsTo(B2bMaster::class, 'ref_master_id');
    }

    public function cartMaster(){
        return  $this->belongsTo(CartMaster::class, 'master_id');
    }

    public function notes(){
    	return $this->morphMany(Note::class, 'noteable');
    }

    public static function HaveRegister($session_id, $user_id){
        $cart = self::with(['userId', 'discountMethod'])->where('session_id', $session_id)
                ->where('user_id', $user_id)
                // ->where('status_id', 327)
                ->first();
        return $cart;
    }

    public function scopeJoinInsights($query){

        $payments = DB::raw('(
            select master_id, payment_status, sum(paid_in) as paid_in, sum(paid_out) as paid_out
            from payments
            group by master_id, payment_status
            ) as payments');

        $qr = $query->join('constants as countries', function($join){
            $join->on('countries.id', '=', 'users.country_id');
            $join->where('countries.post_type', 'countries');
            if(request()->has('country_id') && request()->country_id!=-1){
                $join->where('countries.id', request()->country_id);
            }
        })
        ->leftJoin('cart_masters', function($join){
            $join->on('carts.master_id', 'cart_masters.id');
            $join->where('cart_masters.type_id', 374);
        })
        ->leftJoin($payments, function($join){
            $join->on('payments.master_id', 'cart_masters.id');
            if(request()->has('payment_status') && request()->payment_status != -1 && request()->payment_status != 332){
                $join->whereIn('payments.payment_status',[request()->payment_status]);
            }
        })
        // ->leftJoin('payments', function($join){
        //     $join->on('payments.master_id', 'cart_masters.id');
        //     // $join->on('payments.master_id', 'carts.id');
        //     if(request()->has('payment_status') && request()->payment_status != -1 && request()->payment_status != 332){
        //         $join->whereIn('payments.payment_status',[request()->payment_status]);
        //     }
        //     // if(request()->has('register_from') && !is_null(request()->register_from)){
        //     //     $join->whereDate('payments.paid_at', '>=', request()->register_from);
        //     // }
        //     // if(request()->has('register_to') && !is_null(request()->register_to)){
        //     //     $join->whereDate('payments.paid_at', '<=', request()->register_to);
        //     // }
        // })
        ->join('training_options', function($join){
            $join->on('training_options.id', 'carts.training_option_id');
            if(request()->has('training_option_id') && request()->training_option_id!=-1){
                $join->where('training_options.constant_id', request()->training_option_id);
            }
        })
        ->join('constants as methods', function($join){
            $join->on('methods.id', '=', 'training_options.constant_id');
            $join->where('methods.parent_id', 10);
            if(request()->has('training_option_id') && request()->training_option_id!=-1){
                $join->where('methods.id', request()->training_option_id);
            }
        })
        ->join('sessions', function($join){
            $join->on('sessions.id', 'carts.session_id');
            if(request()->has('session_from') && !is_null(request()->session_from)){
                $join->whereDate('sessions.date_from', '>=', request()->session_from);
            }
            if(request()->has('session_to') && !is_null(request()->session_to)){
                $join->whereDate('sessions.date_from', '<=', request()->session_to);
            }
        })
        ->join('courses', 'courses.id', 'carts.course_id')
        ->join('post_morphs', function($join){
            $join->on('courses.id', '=', 'post_morphs.postable_id');
            $join->where('postable_type', 'App\Models\Training\Course');
            $join->whereNull('table_id');
            if(request()->has('category_id') && request()->category_id!=-1){
                $join->where('post_morphs.constant_id', request()->category_id);
            }
        })
        ->join('constants', function($join){
            $join->on('constants.id', '=', 'post_morphs.constant_id');
            $join->where('constants.post_type', 'course');
        });

        if(request()->has('course_id') && request()->course_id!=-1){
            $qr->where('carts.course_id', request()->course_id);
        }
        if(request()->has('session_id') && request()->session_id!=-1){
            $qr->where('carts.session_id', request()->session_id);
        }
        // if(!request()->has('coin_id_insights') || request()->coin_id_insights==-1){
        //     $qr->where('carts.coin_id',334); //SAR
        // }
        if(request()->has('coin_id_insights') && request()->coin_id_insights!=-1){
            $qr->where('carts.coin_id', request()->coin_id_insights);
        }else{
            $qr->where('carts.coin_id',334); //SAR
        }
        if(request()->has('register_from') && !is_null(request()->register_from)){
            $qr->whereDate('carts.registered_at', '>=', request()->register_from);
        }
        if(request()->has('register_to') && !is_null(request()->register_to)){
            $qr->whereDate('carts.registered_at', '<=', request()->register_to);
        }
        if(request()->has('register_to')){
            if(request()->payment_status==332){
                $qr->where('carts.total', 0)
                   ->where('carts.payment_status', '!=', 332);
            }
            else if(request()->payment_status==68){
                $qr->where('payments.paid_in', '!=', 0);
            }
        }
        $qr->whereNull('carts.trashed_status');
        $qr->where('carts.payment_status', '!=', 383);
        // $qr->whereNotIn('carts.payment_status', [378, 379, 375, 332]);
        // if(request()->has('payment_status') && request()->payment_status != -1 && request()->payment_status != 332){
        //     $qr->where('payments.payment_status',request()->payment_status);
        //     // $join->whereIn('payments.payment_status',[request()->payment_status]);
        // }

        return $qr;
    }

    public static function GetRetarget($retarget_email_id=328){

        $retarget_email_date = null;
        if($retarget_email_id==362)
        {
            $retarget_email_id = 363;
            $retarget_email_date = DateTimeNow();
        }
        else if($retarget_email_id==364)
        {
            $retarget_email_id = 365;
            $retarget_email_date = DateTimeNow();
        }
        else if($retarget_email_id==366)
        {
            $retarget_email_id = 367;
            $retarget_email_date = DateTimeNow();
        }
        return compact('retarget_email_id', 'retarget_email_date');
    }

    public function attendants(){
        return $this->hasMany(Attendant::class, 'cart_id');
    }

    public function attendType(){
        return $this->belongsTo(Constant::class, 'attend_type_id', 'id');
    }

    public static function UpdateReterget($cart){
        $retarget_email_id = $cart->retarget_email_id + 1;
        Cart::where('id', $cart->id)->update([
            'retarget_email_id'=>$retarget_email_id,
        ]);
    }


    static function getSales($session_id,$with_vat,$payment_status){
        if($session_id){
            $course_sales = Cart::where('session_id', $session_id)
                ->whereNull('trashed_status');
                if($payment_status){
                    $course_sales =  $course_sales->where('payment_status', $payment_status);
                }
                // ->where('coin_id', 335)
                if($with_vat == 455 || $with_vat == -1)
                    $course_sales =  $course_sales->select(DB::raw('(sum(total_after_vat)-sum(refund_value_after_vat)) as total_after_vat, coin_id, count(id) as trainees_no'));
                else
                     $course_sales =  $course_sales->select(DB::raw('(sum(total)-sum(refund_value_before_vat)) as total_after_vat, coin_id, count(id) as trainees_no'));

                $course_sales =  $course_sales->groupBy('session_id', 'coin_id')
                ->get()
                ->toArray();
            return $course_sales;
        }else{
            return null;
        }
    }
}
