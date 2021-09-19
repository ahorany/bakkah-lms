<?php

namespace App\Traits;

use App\Constant;

trait ConstantTrait
{
    public function constant(){
        return $this->belongsTo(Constant::class, 'constant_id', 'id');
    }
}
