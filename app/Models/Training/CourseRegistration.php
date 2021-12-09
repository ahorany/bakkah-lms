<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    protected $guarded = [];
    protected $table = 'courses_registration';

    public function course(){
        return $this->belongsTo('App\Models\Training\Course','course_id');
    }

}
