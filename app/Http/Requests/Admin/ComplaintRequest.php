<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'partner_id' => 'required|not_in:-1',
            'submission_date' => 'required|date',
            'department' => 'required|not_in:-1',
            'status' => 'required|not_in:-1',
            'description' => 'required',
        ];
    }
}
