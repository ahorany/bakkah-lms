<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [];

    public function subunits(){
        return $this->hasMany('App\Models\Training\Unit','parent_id');
    }



    public function course(){
        return $this->belongsTo(Course::class,'course_id');
    }


}
