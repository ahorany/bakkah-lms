<?php

namespace App\Models\Training;

use App\Traits\ImgTrait;
use App\Traits\Json\ExcerptTrait;
use App\Traits\JsonTrait;
use App\Traits\TransTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use TrashTrait, TransTrait, ExcerptTrait, UserTrait, ImgTrait, JsonTrait;
    protected $guarded = ['en_title', 'ar_title','en_excerpt','ar_excerpt'];
}
