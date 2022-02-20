<?php

namespace App\Models\Training;
use App\Eloquent;

class Upload extends Eloquent
{
    public function uploadable()
    {
    	return $this->morphTo();
    }
}
