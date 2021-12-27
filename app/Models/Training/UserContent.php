<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserContent extends Model
{
    protected $guarded = [];


    public function content(){
        return $this->belongsTo(Content::class,'content_id');
    }



}
