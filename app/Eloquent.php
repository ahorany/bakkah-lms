<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\SEO\Seo;
use App\Models\SEO\Postkeyword;
use App\Traits\TrashTrait;
use App\User;

class Eloquent extends Model
{
    use TrashTrait;

    //Moved to UserTrait
    //Moved to every specific model
    protected $guarded = [];

    //Moved to UserTrait
	public function getPublishedAtAttribute(){
        $val = GetValueByLang($this->user->name??null);
        $val .= '<small class="d-block">'.__('admin.created_at').': <i>'.$this->created_at->toDayDateTimeString().'</i></small>';
        if(!is_null($this->updated_at))
            $val .= '<small class="d-block">'.__('admin.updated_at').': <i>'.$this->updated_at->toDayDateTimeString().'</i></small>';
        return $val;
    }

    //Moved to UserTrait
    public function user(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    //Moved to SeoTrait
    public function seo(){
        return $this->morphOne(Seo::class, 'seoable');
    }

    //Moved to SeoTrait
    public function postkeyword(){
        return $this->morphMany(Postkeyword::class, 'seokeywordable');
    }
}
