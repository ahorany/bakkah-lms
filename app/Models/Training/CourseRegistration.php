<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;
use DB;

class CourseRegistration extends Model
{
    protected $guarded = [];
    protected $table = 'courses_registration';

    public function course(){
        return $this->belongsTo('App\Models\Training\Course','course_id');
    }

    public static function getAssigned($role_type_id)//511 OR 512
    {
        $branch_id = getCurrentUserBranchData()->branch_id;

        $sql = DB::table('courses_registration')
        ->join('roles',function ($join) use($role_type_id,$branch_id){
            $join->on('roles.id','=','courses_registration.role_id')
                ->where('roles.role_type_id',$role_type_id)
                ->whereNull('roles.deleted_at')
                ->where('roles.branch_id',$branch_id);
        })
        ->join('courses',function ($join) use($branch_id){
            $join->on('courses.id','=','courses_registration.course_id')
                ->whereNull('courses.deleted_at')
                ->where('courses.branch_id',$branch_id);
        });

        return $sql;
    }


    public static function getCoursesNo()
    {
        $branch_id = getCurrentUserBranchData()->branch_id;

        $sql = DB::table('courses_registration')
        ->join('roles',function ($join) use($branch_id){
            $join->on('roles.id','=','courses_registration.role_id')
                ->where('roles.role_type_id',512)
                ->whereNull('roles.deleted_at')
                ->where('roles.branch_id',$branch_id);
        })
        ->join('courses',function ($join) use($branch_id){
            $join->on('courses.id','=','courses_registration.course_id')
                ->whereNull('courses.deleted_at')
                ->where('courses.branch_id',$branch_id);
        })
        ->join('users','users.id','courses_registration.user_id')
        ->join('user_branches',function ($join) use($branch_id){
            $join->on('user_branches.user_id','=','users.id')
        ->where('user_branches.deleted_at',null)
        ->where('user_branches.branch_id',$branch_id);
        });
        return $sql;
    }



}
