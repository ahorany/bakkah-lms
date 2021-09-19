<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupInvAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'organization_id'=>'required|exists:organizations,id',//required
            'owner_user_id'=>'required|exists:users,id',
            'follow_up_date'=>'nullable|date',
            'total'=>'',
            'vat'=>'',
            'vat_value'=>'',
            'total_after_vat'=>'',
            'coin_id'=>'',
            'coin_price'=>'',
        ];
    }
}
