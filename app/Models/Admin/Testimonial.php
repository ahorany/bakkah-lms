<?php

namespace App\Models\Admin;

use App\Constant;
use App\Models\Training\Course;
use App\Traits\DateTrait;
use App\Traits\ImgTrait;
use App\Traits\Json\ExcerptTrait;
use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use ImgTrait;
    use ExcerptTrait, TrashTrait, SeoTrait, UserTrait;
    use DateTrait;

    protected $guarded = ['en_excerpt', 'ar_excerpt'];

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function status(){
        return $this->belongsTo(Constant::class,'status');
    }
}
