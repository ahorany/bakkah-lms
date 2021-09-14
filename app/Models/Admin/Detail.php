<?php

namespace App\Models\Admin;

use App\Traits\ConstantTrait;
use App\Traits\Json\DetailsTrait;
use App\Traits\PostMorphTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use DetailsTrait;
    use PostMorphTrait;
    use TrashTrait;
    use UserTrait;
    use ConstantTrait;

    public $guarded = ['en_details', 'ar_details'];

    public function detailable()
    {
        return $this->morphTo();
    }

}
