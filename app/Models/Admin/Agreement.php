<?php

namespace App\Models\Admin;

use App\Traits\ImgTrait;
use App\Traits\SeoTrait;
use App\Traits\JsonTrait;
use App\Traits\TransTrait;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Partner;

class Agreement extends Model
{
    use ImgTrait;
    use JsonTrait;
    use UserTrait;
    use SeoTrait;
    use TransTrait;
    use TrashTrait;

    public $guarded = [];

    public function partner(){
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function notes(){
    	return $this->morphMany(Note::class, 'noteable');
    }

}
