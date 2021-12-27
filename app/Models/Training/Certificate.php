<?php

namespace App\Models\Training;

use App\Constant;
use App\Traits\ImgTrait;
use App\Traits\Json\ExcerptTrait;
use App\Traits\JsonTrait;
use App\Traits\PostMorphTrait;
use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Laravel\Scout\Searchable;

class Certificate extends Model
{
    use TrashTrait, ImgTrait, PostMorphTrait;
    use JsonTrait, ExcerptTrait, UserTrait, SeoTrait;

    protected $guarded = ['en_title', 'ar_title'];
}


