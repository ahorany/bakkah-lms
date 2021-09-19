<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AgreementRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'partner_id' => 'required',
            'signing_date' => 'required',
            'expired_date' => 'required',
            'is_active' => '',
        ];
    }
}
