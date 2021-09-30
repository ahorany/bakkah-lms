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

    public function exams(){
        return $this->hasMany(Exam::class,'content_id');
    }

    public function exam(){
        return $this->hasOne(Exam::class,'content_id');
    }

    public function details(){
        return $this->hasOne('App\Models\Training\ContentDetails','content_id');
    }

    public function questions(){
        return $this->hasMany('App\Models\Training\Question','exam_id');
    }


    public function course(){
        return $this->belongsTo(Course::class,'course_id');
    }
}
