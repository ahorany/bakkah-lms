<?php

namespace App;

use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\JsonTrait;

class Infastructure extends Model
{
	use JsonTrait, TrashTrait;
	use SeoTrait;
}
