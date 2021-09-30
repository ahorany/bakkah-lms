<?php

namespace App\Models\Training;


use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];


    public function answers(){
        return $this->hasMany('App\Models\Training\Answer','question_id');
    }

    public function exam(){
        return $this->belongsTo('App\Models\Training\Content','exam_id');
    }

}
