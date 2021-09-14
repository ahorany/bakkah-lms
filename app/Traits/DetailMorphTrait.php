<?php

namespace App\Traits;

use App\Models\Admin\Detail;

Trait DetailMorphTrait
{
    public function details()
    {
        return $this->morphMany(Detail::class, 'detailable');
    }

    public function detail()
    {
        return $this->morphOne(Detail::class, 'detailable');
    }
}
