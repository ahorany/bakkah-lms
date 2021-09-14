<?php

namespace App\Models\Admin;

use App\Traits\ImgTrait;
use App\Traits\SeoTrait;
use App\Traits\JsonTrait;
use App\Traits\TransTrait;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use TrashTrait, ImgTrait;
    use JsonTrait;
    use UserTrait;
    use SeoTrait;
    use TransTrait;

    public $guarded = [];

    public function type(){
        return $this->belongsTo('App\Constant', 'constant_id');
    }

}
