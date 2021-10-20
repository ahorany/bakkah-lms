<?php

namespace App\Models\Training;


use App\Traits\ImgTrait;
use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use TrashTrait,ImgTrait , UserTrait, SeoTrait;
    protected $guarded = [];

}
