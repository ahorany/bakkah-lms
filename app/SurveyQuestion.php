<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $guarded = [];

    public function survey_options(){
        return $this->hasMany(SurveyOption::class, 'master_id');
    }
}
