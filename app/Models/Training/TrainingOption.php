<?php

namespace App\Models\Training;

use App\Constant;
use App\Traits\ConstantTrait;
use App\Traits\DetailMorphTrait;
use App\Traits\Json\DetailsTrait;
use App\Traits\JsonTrait;
use App\Traits\PostMorphTrait;
use App\Traits\TrashTrait;
use App\Traits\UserTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Upload;

class TrainingOption extends Model
{
    use UserTrait;
    use TrashTrait, JsonTrait, DetailsTrait;
    use ConstantTrait, DetailMorphTrait, PostMorphTrait;

    protected $guarded = ['en_details', 'ar_details'];

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function feature(){
        return $this->belongsTo(CartFeature::class, 'feature_id', 'id');
    }

    //The new is: trainingOptionFeatures
    public function features(){
        return $this->hasMany(TrainingOptionFeature::class, 'training_option_id', 'id');
    }

    //The old is: features, TrainingOptionFeature
    public function TrainingOptionFeature(){
        return $this->hasMany(TrainingOptionFeature::class, 'training_option_id');
    }

    //The old is: features, TrainingOptionFeature
    public function trainingOptionFeatures(){
        return $this->hasMany(TrainingOptionFeature::class, 'training_option_id');
    }

    public function session(){
        return $this->hasOne(Session::class, 'training_option_id')->orderBy('date_from', 'asc');
    }

    public function sessions(){
        return $this->hasMany(Session::class, 'training_option_id')->orderBy('date_from', 'asc');
    }

    public function type(){
        return $this->belongsTo(Constant::class, 'constant_id');
    }

    public function bundle(){
        return $this->hasOne(Bundle::class, 'training_option_type_id', 'constant_id');
    }

    public function getTrainingNameAttribute(){
        $trans_title = $this->course->trans_title??null;
        if(isset($this->constant)){
            return $trans_title . ' | ' . $this->constant->trans_name??null;
        }
        else{
            return $trans_title;
        }
    }

    public function getCourseNameAttribute(){
        $trans_title = $this->course->trans_title??null;
        return $trans_title;
    }

    public function getENTrainingNameAttribute(){
        $en_title = $this->course->en_title??null;
        return $en_title . ' | ' . $this->constant->en_name;
    }

    public function getTrainingShortNameAttribute(){
        $trans_short_title = $this->course->trans_short_title??null;
        return $trans_short_title . ' | ' . $this->constant->trans_name;
    }

    public function lms(){
        return $this->belongsTo(Constant::class,'lms_id');
    }

    public function ExamSimulation(){
        return $this->belongsTo(TrainingOption::class, 'exam_simulation_id');
    }

    public function uploads(){
    	return $this->morphMany(Upload::class, 'uploadable');
    }

}
