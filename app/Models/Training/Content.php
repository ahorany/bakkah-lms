<?php

namespace App\Models\Training;

use App\Models\Admin\Upload;
use App\Traits\FileTrait;
use App\Traits\ImgTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use TrashTrait,FileTrait;
    protected $guarded = [];



}
