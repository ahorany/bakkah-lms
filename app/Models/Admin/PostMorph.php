<?php

namespace App\Models\Admin;

use App\Traits\ConstantTrait;
use Illuminate\Database\Eloquent\Model;

class PostMorph extends Model
{
    use ConstantTrait;
	protected $guarded = [];

    public function postable()
    {
        return $this->morphTo();
    }

    public function accordions(){
        return $this->hasMany(Accordion::class, 'master_id')->orderby('order');
    }

//    public function detail(){
//        return $this->hasOne(Detail::class, 'master_id');
//    }
}
