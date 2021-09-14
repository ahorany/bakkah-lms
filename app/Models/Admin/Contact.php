<?php

namespace App\Models\Admin;

use App\Constant;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use TrashTrait, UserTrait;

    protected $guarded = [];

    public function requestType(){
        return $this->belongsTo(Constant::class, 'request_type', 'id');
    }
}
