<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class TrainingOptionFeature extends Model
{
    protected $guarded = [];

    public function feature() {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public function scopeTrainingOptionFeatures($query, $training_option_id) {
        $trainingOptionFeatures = $query->where('training_option_id', $training_option_id)->with(['feature'])
        ->get()
        ->map(function($trainingOptionFeature){
            return [
                'id' => $trainingOptionFeature->id,
                'title' => $trainingOptionFeature->feature->trans_title,
                'price' => $trainingOptionFeature->final_price,
                'total_after_vat' => $trainingOptionFeature->total_after_vat,
                'is_include' => $trainingOptionFeature->is_include,
            ];
        });

        return $trainingOptionFeatures;
    }

    public function getFinalPriceAttribute() {
        return  (GetCoinId() == 334) ? $this->price : $this->price_usd;
    }

    public function getTotalAfterVatAttribute() {
        return  (GetCoinId() == 334) ? ($this->price * (VAT / 100)) + $this->price : $this->price_usd;
    }

}
