<?php

namespace App\Models\Admin;

use App\User;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use TrashTrait;
    public $guarded = [];

    public function noteable()
    {
        return $this->morphTo();
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }

}
