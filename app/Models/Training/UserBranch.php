<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserBranch extends Model
{
    use TrashTrait;

    protected $table = "user_branches";
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }


}
