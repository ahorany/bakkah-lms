<?php

namespace App;

use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\JsonTrait;

class Infrastructure extends Model
{
	use JsonTrait, TrashTrait;
	use SeoTrait;

    protected $table = 'infastructures';
}
