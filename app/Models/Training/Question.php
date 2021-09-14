<?php
namespace App\Models\Training;

use App\Models\Training\Exam;
use App\Models\Training\Answer;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use App\Traits\JsonTrait;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use UserTrait, TrashTrait, JsonTrait;

    protected $guarded = [];

    public function exams(){
        return $this->belongsTo(Exam::class, 'master_id');
    }

    public function answers(){
        return $this->hasOne(Answer::class, 'question_id');
    }

    public function carts(){
        return $this->hasMany(Cart::class, 'cart_id');
    }

}
