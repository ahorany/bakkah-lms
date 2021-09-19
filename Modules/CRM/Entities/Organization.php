<?php

namespace Modules\CRM\Entities;

use App\Traits\ImgTrait;
use App\Traits\JsonTrait;
use App\Traits\UserTrait;
use App\Traits\TransTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use TrashTrait, TransTrait, UserTrait, ImgTrait, JsonTrait;
    protected $guarded = ['en_title', 'ar_title', 'en_name', 'ar_name'];
}
