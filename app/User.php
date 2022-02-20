<?php

namespace App;

use App\Models\Training\Content;
use App\Models\Training\Course;
use App\Models\Training\Group;
use App\Traits\ImgTrait;
use App\Traits\JsonTrait;
use App\Traits\TrashTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use Notifiable, TrashTrait, ImgTrait, JsonTrait , HasRoles;


    protected $date = [
        'updated_at', 'created_at', 'deleted_at',
    ];

    protected $fillable = [
         'name', 'email', 'password','gender_id', 'created_by','updated_by', 'user_type',
         'job_title', 'company','mobile','lang','bio','last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }



    public function gender(){
        return $this->belongsTo(Constant::class, 'gender_id', 'id');
    }


    public function courses(){
        return $this->belongsToMany(Course::class,'courses_registration','user_id')->withPivot('user_id' ,'course_id','rate', 'progress');
    }

    public function user_contents(){
        return $this->belongsToMany(Content::class,'user_contents','user_id','content_id');
    }

    public function groups(){
        return $this->belongsToMany(Group::class,'user_groups','user_id','group_id');
    }


    public static function delegate_user_id(){
        if(request()->has('delegate_user_id')){
            return request()->delegate_user_id;
        }
        return auth()->id();
    }
}
