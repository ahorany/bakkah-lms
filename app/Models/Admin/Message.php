<?php

namespace App\Models\Admin;

use App\Traits\Json\DetailsTrait;
use App\Traits\JsonTrait;
use App\Traits\TransTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use JsonTrait, DetailsTrait;
    use TrashTrait, TransTrait ,UserTrait;

    public $guarded = [];
}
