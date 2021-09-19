<?php

namespace App\Models\Training;

use App\User;
use App\Traits\TrashTrait;
use App\Models\Training\TrainingOption;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use TrashTrait;

    protected $guarded = [];

    public function trainingOption(){
        return $this->belongsTo(TrainingOption::class,'training_option_id');
    }

    public function userId(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
