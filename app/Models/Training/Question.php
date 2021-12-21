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

    public function units(){
        return $this->belongsToMany(Unit::class,'question_units','question_id','unit_id');
    }

}
