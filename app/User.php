<?php

namespace App;

use App\Models\Training\Branche;
use App\Models\Training\Content;
use App\Models\Training\Course;
use App\Models\Training\Group;
use App\Traits\ImgTrait;
use App\Traits\JsonTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DB;


class User extends Authenticatable implements JWTSubject
{

    use Notifiable, TrashTrait, ImgTrait, JsonTrait , HasRoles;


    protected $date = [
        'updated_at', 'created_at', 'deleted_at',
    ];

    protected $fillable = [
         'name', 'email', 'password','gender_id', 'created_by','updated_by', 'user_type',
         'job_title', 'company','mobile','lang','bio','last_login','is_logout'
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


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
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

    public function gender(){
        return $this->belongsTo(Constant::class, 'gender_id', 'id');
    }

    public function courses(){
        return $this->belongsToMany(Course::class,'courses_registration','user_id')->withPivot('user_id' ,'course_id','rate', 'progress','paid_status','session_id');
    }

    public function user_contents(){
        return $this->belongsToMany(Content::class,'user_contents','user_id','content_id');
    }

    public function groups(){
        return $this->belongsToMany(Group::class,'user_groups','user_id','group_id');
    }

    public function branches(){
        return $this->belongsToMany(Branche::class,'user_branches','user_id','branch_id')->withPivot('user_id','branch_id','name','bio');
    }

    /**
     * A model may have multiple roles.
     */
    public function roles(): BelongsToMany
    {
        $rel =  $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        );


        $current_user_branch = getCurrentUserBranchData();

        if (!session('is_super_admin')) {
            $rel = $rel->where(function ($q) use ($current_user_branch){
                 $q->where('roles.branch_id',$current_user_branch->branch_id??1);
            });
        }

        return $rel;
    }


    public static function delegate_user_id(){
        if(request()->has('delegate_user_id')){
            return request()->delegate_user_id;
        }
        return auth()->id();
    }

    public static function getLearnersNo()
    {
        $branch_id = getCurrentUserBranchData()->branch_id;

        $sql  = DB::table('model_has_roles')
                ->join('roles',function ($join){
                        $join->on('roles.id','=','model_has_roles.role_id')
                ->where('roles.role_type_id',512)
                ->where('roles.deleted_at',null)
                ->where('roles.branch_id',getCurrentUserBranchData()->branch_id);
                })
                ->join('users','users.id','model_has_roles.model_id');
        return $sql;


    }
}
