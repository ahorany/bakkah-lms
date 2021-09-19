<?php

namespace App;

use App\Models\Training\Course;
use App\Traits\JsonTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class ProfileQuestionUser extends Model
{
    use JsonTrait, TrashTrait;
    protected $guarded = [];

    public function products(){
        return $this->belongsTo(Course::class, 'course_id');
    }

}
