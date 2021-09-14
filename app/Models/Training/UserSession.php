<?php

namespace App\Models\Training;

use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserSession extends Model
{
    // use UserTrait;
    use TrashTrait;

    protected $guarded = [];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

}
