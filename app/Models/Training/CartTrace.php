<?php

namespace App\Models\Training;

use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class CartTrace extends Model
{
    use TrashTrait;

    protected $guarded = [];
}
