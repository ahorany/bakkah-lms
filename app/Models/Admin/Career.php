<?php

namespace App\Models\Admin;

use App\Traits\TrashTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use TrashTrait;
    use Sluggable;


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ! empty($this->slug) ? 'slug' : 'title',
            ]
        ];
    }
}
