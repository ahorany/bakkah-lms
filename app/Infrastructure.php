<?php

namespace App;

use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\JsonTrait;

class Infrastructure extends Model
{
	use JsonTrait, TrashTrait;

    protected $table = 'infrastructures';
}
