<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use TrashTrait;
    protected $guarded = [];
    protected $table = "criteria";

    public function branches(){
        return $this->belongsToMany(Branche::class,'branches_points_criteria','points_criteria_id','branche_id')->withPivot('points_criteria_id' ,'branche_id','points');
    }


}
