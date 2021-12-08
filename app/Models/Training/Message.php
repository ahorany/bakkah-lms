<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use TrashTrait;
    protected $guarded = [];

    public function courses(){
        return $this->belongsTo('App\Models\Training\CourseRegistration','course_id');
    }

    public function user(){
        return $this->belongsTo('App\User','user_to');
    }

}
