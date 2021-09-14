<?php

namespace App\Models\Admin\Service;

use App\Traits\Json\DetailsTrait;
use App\Traits\Json\ExcerptTrait;
use App\Traits\JsonTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;

class ServiceArchive extends Model
{
    use TrashTrait, UserTrait, JsonTrait, ExcerptTrait, DetailsTrait;

    public $guarded = ['ar_title', 'en_title', 'en_excerpt', 'ar_excerpt', 'en_details', 'ar_details'];
}
