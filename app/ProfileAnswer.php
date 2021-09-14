<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileAnswer extends Model
{
    protected $guarded = [];

    public function profile_question_users(){
    	return $this->hasMany(ProfileQuestionUser::class, 'profile_answer_id');
    }
}
