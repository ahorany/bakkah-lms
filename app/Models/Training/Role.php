<?php

namespace App\Models\Training;


use App\Traits\TrashTrait;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use TrashTrait;

}
