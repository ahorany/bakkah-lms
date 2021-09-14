<?php

namespace Modules\CRM\Entities;

use App\Constant;
use App\Models\Admin\Note;
use App\Models\Training\CartMaster;
use App\Models\Training\Course;
use App\Models\Training\Session;
use Modules\CRM\Entities\Organization;
use App\Traits\JsonTrait;
use App\Traits\UserTrait;
use App\Traits\TrashTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupInvoiceMaster extends Model
{
    // protected $fillable = [];
    use TrashTrait, UserTrait, JsonTrait;

    protected $guarded = ['en_title', 'ar_title', 'en_name', 'ar_name'];

    public function organization(){
        return  $this->belongsTo(Organization::class);
    }

    public function master(){
        return  $this->belongsTo(CartMaster::class);
    }

    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function session(){
        return $this->belongsTo(Session::class, 'session_id')->orderBy('date_from', 'asc');
    }

    public function userId(){
        return $this->belongsTo(User::class, 'owner_user_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function groupInvoiceCart(){
        return $this->hasOne(GroupInvoiceCart::class, 'group_invoice_master_id', 'id');
    }

    public function getPublishedAtAttribute(){
        $val = GetValueByLang($this->user->name??null);
        $val .= '<small class="d-block">'.__('admin.created_at').': <i>'.$this->created_at->toDayDateTimeString().'</i></small>';
        if(!is_null($this->updated_at))
            $val .= '<small class="d-block">'.__('admin.updated_at').': <i>'.$this->updated_at->toDayDateTimeString().'</i></small>';
        return $val;
    }
}
