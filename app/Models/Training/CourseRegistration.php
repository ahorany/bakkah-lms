<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    protected $guarded = [];
    protected $table = 'courses_registration';

}
