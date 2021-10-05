<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UserExam extends Model
{
    protected $guarded = [];


    public function exam(){
        return $this->belongsTo(Exam::class,'exam_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }


    public function user_answers(){
        return $this->belongsToMany(Answer::class,'user_answers','user_exam_id','answer_id');
    }



}
