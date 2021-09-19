<?php

namespace App\Models\Training;

use App\Constant;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use App\Models\Training\CartMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Payment extends Model
{
    use UserTrait, TrashTrait;

    protected $casts = [
        'paid_at' => 'datetime:Y-m-d',
    ];

    protected $guarded = [];

    public function cart(){
        return $this->belongsTo(Cart::class, 'master_id', 'id');
    }

    public function cartMaster(){
        return $this->belongsTo(cartMaster::class, 'master_id', 'id');
    }

//    public function getPaid_AtAttribute($value){
//        return $value->paid_at->format('d/m/y');
//    }

    public function paymentStatus(){
        return $this->belongsTo(Constant::class, 'payment_status', 'id');
    }

    public function coins(){
        return $this->belongsTo(Constant::class, 'coin_id', 'id');
    }
}
