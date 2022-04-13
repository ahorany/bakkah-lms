<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $table = 'discussions';
    protected $guarded = [];

    public function message(){
        return $this->belongsTo(Message::class,'message_id');
    }

}
