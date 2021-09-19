<?php

namespace App\Models\Training;

use App\Traits\JsonTrait;
use App\Traits\TransTrait;
use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    protected $guarded = [];
    use TrashTrait, TransTrait, JsonTrait;
    protected $dates = ['deleted_at'];
    //

    public function TrainingOptionFeature(){
        return $this->hasOne(TrainingOptionFeature::class);
    }

    public function xeroAccount(){
    	return $this->morphOne(XeroAccount::class, 'xeroAccountable');
    }
}
