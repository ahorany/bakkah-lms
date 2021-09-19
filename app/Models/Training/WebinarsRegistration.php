<?php

namespace App\Models\Training;

use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class WebinarsRegistration extends Model
{
    use UserTrait, TrashTrait;

    protected $guarded = [];

    public function webinar(){
        return $this->belongsTo(Webinar::class);
    }

    public function userId(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
