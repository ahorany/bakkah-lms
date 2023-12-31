<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use TrashTrait;
    protected $guarded = [];

    public function course(){
        return $this->belongsTo('App\Models\Training\CourseRegistration','course_id','course_id');
    }

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function replies(){
        return $this->hasMany('App\Models\Training\Reply', 'message_id');
    }

    public function likes(){
        return $this->hasMany('App\Models\Training\Like', 'likeable_id')
        ->where('likeable_type', 508);
    }
}
