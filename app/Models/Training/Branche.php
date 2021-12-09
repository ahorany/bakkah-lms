<?php

namespace App\Models\Training;


use App\Traits\ImgTrait;
use App\Traits\SeoTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Branche extends Model
{
    use TrashTrait,ImgTrait , SeoTrait;
    protected $guarded = [];

    public function criteria(){
        return $this->belongsToMany(Criteria::class,'branches_points_criteria','branche_id','points_criteria_id')->withPivot('points_criteria_id' ,'branche_id','points');
    }


//    public function users(){
//        return $this->belongsToMany(User::class,'user_groups','group_id')->withPivot('user_id' ,'group_id','role_id');
//    }
//
//    public function courses(){
//        return $this->belongsToMany(Course::class,'course_groups','group_id')->withPivot('course_id' ,'group_id');
//    }
//
//    public function course_registration(){
//        return $this->belongsToMany(CourseRegistration::class,'course_registration_groups','group_id','course_registration_id')->withPivot('course_registration_id' ,'group_id');
//    }


}
