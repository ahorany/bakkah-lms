<?php

namespace App\Models\Training;

use App\Traits\JsonTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use TrashTrait;
    use JsonTrait, UserTrait;


//    use Searchable;

    protected $guarded = ['en_title', 'ar_title'];



}
