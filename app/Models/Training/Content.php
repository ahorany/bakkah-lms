<?php

namespace App\Models\Training;

use App\Models\Admin\Upload;
use App\Traits\FileTrait;
use App\Traits\ImgTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use TrashTrait,FileTrait;
    protected $guarded = [];


    public function contents(){
        return $this->hasMany('App\Models\Training\Content','parent_id');
    }

    public function details(){
        return $this->hasOne('App\Models\Training\ContentDetails','content_id');
    }
}
