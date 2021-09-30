<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use TrashTrait;
    protected $guarded = [];


    public function question(){
        return $this->belongsTo('App\Models\Training\Question','question_id');
    }

}
