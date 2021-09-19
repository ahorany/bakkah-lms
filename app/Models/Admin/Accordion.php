<?php

namespace App\Models\Admin;

use App\Traits\Json\DetailsTrait;
use App\Traits\Json\TitleTrait;
use App\Traits\PostMorphTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;

class Accordion extends Model
{
    use PostMorphTrait;
    use TrashTrait;
    use TitleTrait, DetailsTrait;
    use UserTrait;

    public $guarded = ['ar_title', 'en_title', 'en_details', 'ar_details'];
}
