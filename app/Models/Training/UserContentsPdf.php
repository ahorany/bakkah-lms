<?php

namespace App\Models\Training;

use App\Traits\DetailMorphTrait;
use App\Traits\ImgTrait;
use App\Traits\Json\ExcerptTrait;
use App\Traits\JsonTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Cache;

class UserContentsPdf extends Model
{
    protected $guarded = [];
}
