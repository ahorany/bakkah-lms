<?php

namespace App;

use App\Traits\ImgTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes, ImgTrait;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
