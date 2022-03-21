<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use TrashTrait;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function likes(){
        return $this->hasMany('App\Models\Training\Like', 'likeable_id')
        ->where('likeable_type', 509);
    }
}
