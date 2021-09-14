<?php

namespace App\Models\Admin;
use App\Eloquent;

class Upload extends Eloquent
{
    public function uploadable()
    {
    	return $this->morphTo();
    }
}
