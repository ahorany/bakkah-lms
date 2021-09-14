<?php

namespace App\Traits;

use App\User;

trait UserTrait
{
    public function getPublishedAtAttribute(){
        $val = GetValueByLang($this->user->name??null);
        // toDayDateTimeString()
        $val .= '<small class="d-block">'.__('admin.created_at').': <i>'.\Carbon\Carbon::parse($this->created_at)->isoFormat('D MMM Y').'</i></small>';
        if(!is_null($this->updated_at))
            $val .= '<small class="d-block">'.__('admin.updated_at').': <i>'.\Carbon\Carbon::parse($this->updated_at)->isoFormat('D MMM Y').'</i></small>';
            // $val .= '<small class="d-block">'.__('admin.updated_at').': <i>'.$this->updated_at->isoFormat('D MMM Y').'</i></small>';
        return $val;
    }

    public function user(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function userId(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
