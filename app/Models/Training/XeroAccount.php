<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class XeroAccount extends Model
{
    protected $guarded = [];

    public function xeroAccountable()
    {
    	return $this->morphTo();
    }
}
