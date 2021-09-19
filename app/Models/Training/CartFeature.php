<?php

namespace App\Models\Training;

use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class CartFeature extends Model
{
    use TrashTrait;

    protected $guarded = [];

    public function feature(){
        return $this->belongsTo(Feature::class, 'training_option_feature_id');
    }

    public function trainingOptionFeature(){
        return $this->belongsTo(TrainingOptionFeature::class, 'training_option_feature_id');
    }

    public function cart(){
        return $this->belongsTo(Cart::class, 'master_id');
    }
}
