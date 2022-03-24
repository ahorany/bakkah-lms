<?php

namespace App\Models\Training;

use App\Traits\JsonTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;


class Session extends Model
{
    use TrashTrait;
//    use JsonTrait, UserTrait;


//    use Searchable;

    protected $guarded = [];

    public function course(){
        return $this->belongsTo(Course::class,'course_id');
    }


}
