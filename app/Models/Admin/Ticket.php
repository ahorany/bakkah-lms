<?php

namespace App\Models\Admin;

use App\Traits\ImgTrait;
use App\Traits\SeoTrait;
use App\Traits\JsonTrait;
use App\Traits\TransTrait;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use TrashTrait, ImgTrait;
    // use JsonTrait;
    use UserTrait;
    use SeoTrait;
    // use TransTrait;
    public $guarded = [];

    public function issues(){
        return $this->belongsTo('App\Constant', 'issue');
    }

    public function priorities(){
        return $this->belongsTo('App\Constant', 'priority');
    }

    public function states(){
        return $this->belongsTo('App\Constant', 'status');
    }

    public function companies(){
        return $this->belongsTo('App\Constant', 'company');
    }
}
