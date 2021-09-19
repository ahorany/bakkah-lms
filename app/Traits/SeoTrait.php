<?php

namespace App\Traits;

use App\Models\SEO\Postkeyword;
use App\Models\SEO\Seo;

trait SeoTrait
{
    public function seo(){
        return $this->morphOne(Seo::class, 'seoable');
    }

    //I Change postkeyword To postkeywords
    public function postkeyword(){
        return $this->morphMany(Postkeyword::class, 'seokeywordable');
    }

    public function postkeywords(){
        return $this->morphMany(Postkeyword::class, 'seokeywordable');
    }
}
