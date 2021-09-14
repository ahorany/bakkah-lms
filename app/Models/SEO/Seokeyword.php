<?php

namespace App\Models\SEO;

use Illuminate\Database\Eloquent\Model;

class Seokeyword extends Model
{
    protected $guarded = [];

    public function postkeyword(){
    	return $this->hasOne(Postkeyword::class, 'seokeywords_id', 'id');
    }
}
