<?php

namespace App;

use App\Models\Training\Content;
use App\Models\Training\Course;
use App\Models\Training\Group;
use Exception;
use App\Traits\ImgTrait;
use App\Traits\JsonTrait;
use App\Models\Admin\Role;
use App\Traits\TrashTrait;
use App\Models\Training\Cart;
use App\Models\Training\Experience;
use App\Traits\PostMorphTrait;
use App\Models\Training\Social;
use App\Models\Training\Session;
use App\Models\Training\Wishlist;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, TrashTrait, ImgTrait, JsonTrait;
    use PostMorphTrait;

    protected $date = [
        'updated_at', 'created_at', 'deleted_at', 'two_factor_expires_at',
    ];

    protected $fillable = [
        'name', 'email', 'main_email', 'password', 'role_id'
        , 'gender_id', 'created_by', 'updated_by', 'user_type', 'job_title', 'company'
        , 'date_time', 'mobile', 'country', 'city','mail_subscribe', 'ref_id'
        , 'password_lms', 'username_lms', 'country_id'
        , 'trainer_courses_for_certifications', 'locale'
        , 'two_factor_code', 'two_factor_expires_at','headline','lang','bio'
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

    public function generateTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(3);
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = now()->addMinutes(3);
        $this->save();
    }

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

    // ===============================

    public function countries(){
        return $this->belongsTo(Constant::class, 'country_id', 'id');
    }

    public function getTransTrainerCoursesForCertificationsAttribute(){
		$lang = app()->getLocale();
		return json_decode($this->trainer_courses_for_certifications)->$lang??null;
	}

	public function getEnTrainerCoursesForCertificationsAttribute(){
		return json_decode($this->trainer_courses_for_certifications)->en??null;
	}

	public function getArTrainerCoursesForCertificationsAttribute(){
		return json_decode($this->trainer_courses_for_certifications)->ar??null;
	}

	public function setTrainerCoursesForCertificationsAttribute()
	{
		$data = json_encode([
			'en'=>request()->en_trainer_courses_for_certifications,
			'ar'=>request()->ar_trainer_courses_for_certifications
		], JSON_UNESCAPED_UNICODE);

		$this->attributes['trainer_courses_for_certifications'] = $data;
    }

    public function gender(){
        return $this->belongsTo(Constant::class, 'gender_id', 'id');
    }

    // public function role(){
    //     return $this->belongsTo(Role::class, 'role_id', 'id');
    // }
    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user','user_id','role_id');
    }

    public function carts(){
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    public function wishlists(){
        return $this->hasMany(Wishlist::class, 'user_id', 'id');
    }

    public function socials(){
        return $this->hasMany(Social::class, 'user_id', 'id');
    }

    public function experiences(){
        return $this->hasMany(Experience::class, 'user_id', 'id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    /////// LMS //////////

    public function courses(){
        return $this->belongsToMany(Course::class,'courses_registration','user_id')->withPivot('user_id' ,'course_id','rate', 'progress');
    }

    public function user_contents(){
        return $this->belongsToMany(Content::class,'user_contents','user_id','content_id');
    }

    public function groups(){
        return $this->belongsToMany(Group::class,'user_groups','user_id','group_id');
    }

}
