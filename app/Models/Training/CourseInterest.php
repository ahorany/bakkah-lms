<?php

namespace App\Models\Training;

use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CourseInterest extends Model
{
    use UserTrait, TrashTrait;

    protected $guarded = [];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function userId(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
