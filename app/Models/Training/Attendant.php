<?php
namespace App\Models\Training;

use App\Traits\UserTrait;
use App\Models\Admin\Note;
use App\Traits\TrashTrait;
use Modules\CRM\Entities\B2bMaster;
use App\Models\Training\CartFeature;
use Illuminate\Database\Eloquent\Model;
use App\Models\Training\Session;

class Attendant extends Model
{
    protected $guarded = [];

    public function carts(){
        return $this->belongsTo(Cart::class);
    }
    //
}
