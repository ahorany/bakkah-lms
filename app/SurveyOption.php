<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyOption extends Model
{
    protected $guarded = [];

    public function survey_answers(){
        return $this->hasMany(SurveyAnswer::class, 'master_id');
    }
}
