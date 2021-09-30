<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use TrashTrait;
    protected $guarded = [];


    public function content(){
        return $this->belongsTo(Content::class,'content_id');
    }

}
