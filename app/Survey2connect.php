<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey2connect extends Model
{
    protected $guarded = [];

    public function survey_questions(){
        return $this->hasMany(SurveyQuestion::class, 'master_id');
    }

}
