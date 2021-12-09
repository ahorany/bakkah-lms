<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class BranchePointCriteria extends Model
{
    use TrashTrait;
    protected $guarded = [];
    protected $table = "branches_points_criteria";



}
