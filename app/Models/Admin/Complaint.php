<?php

namespace App\Models\Admin;

use App\Traits\ImgTrait;
use App\Traits\SeoTrait;
use App\Traits\JsonTrait;
use App\Traits\TransTrait;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Note;

class Complaint extends Model
{
    use ImgTrait;
    use JsonTrait;
    use UserTrait;
    use SeoTrait;
    use TransTrait;
    use TrashTrait;

    public $guarded = [];

    public function partner(){
        return $this->belongsTo('App\Models\Admin\Partner', 'partner_id');
    }

    public function departments(){
        return $this->belongsTo('App\Constant', 'department');
    }

    public function states(){
        return $this->belongsTo('App\Constant', 'status');
    }

    public function notes(){
    	return $this->morphMany(Note::class, 'noteable');
    }
}
