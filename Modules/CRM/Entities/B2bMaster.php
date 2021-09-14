<?php

namespace Modules\CRM\Entities;

use App\Constant;
use App\Models\Training\Course;
use App\Models\Training\Session;
use App\Models\Training\TrainingOption;
use App\Traits\JsonTrait;
use App\Traits\UserTrait;
use App\Traits\TransTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;


class B2bMaster extends Model
{
    use TrashTrait, TransTrait, UserTrait, JsonTrait;

    protected $guarded = [];

//    protected $table='b2bs';
    public function status(){
        return  $this->belongsTo(Constant::class);
    }

    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function session(){
        return $this->belongsTo(Session::class);
    }


}
