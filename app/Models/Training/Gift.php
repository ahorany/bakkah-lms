<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $table = 'gifts';
    protected $guarded = [];


    public function content(){
        return $this->belongsTo('App\Models\Training\Content','content_id');
    }

}
