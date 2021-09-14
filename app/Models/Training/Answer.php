<?php
namespace App\Models\Training;

use App\Models\Training\Question;
use App\Models\Training\Cart;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use App\Traits\JsonTrait;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use UserTrait, TrashTrait, JsonTrait;

    protected $guarded = [];

    public function questions(){
        return $this->hasOne(Question::class);
    }

    public function carts(){
        return $this->belongsTo(Cart::class);
    }

}
