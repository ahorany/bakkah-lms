<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileQuestion extends Model
{
    protected $guarded = [];

    public function profile_answers(){
        return $this->hasMany(ProfileAnswer::class, 'profile_question_id');
    }
}
