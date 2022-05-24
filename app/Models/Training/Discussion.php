<?php

namespace App\Models\Training;

use App\Traits\TrashTrait;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use TrashTrait;
    protected $table = 'discussions';
    protected $guarded = [];

    public function message(){
        return $this->belongsTo(Message::class,'message_id')->withTrashed();
    }

}
