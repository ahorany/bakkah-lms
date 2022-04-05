<?php

namespace App\Models\Training;


use App\Traits\ImgTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Branche extends Model
{
    use TrashTrait,ImgTrait;
    protected $guarded = ['file'];

}
