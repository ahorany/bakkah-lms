<?php

namespace App\Models\Admin;

use App\Traits\JsonTrait;
use App\Traits\TransTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use JsonTrait, TransTrait, TrashTrait;

    protected $guarded = ['en_name', 'ar_name'];

    public function infrastructures()
    {
        return $this->belongsToMany('App\Infrastructure');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
