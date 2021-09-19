<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ConstantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'en_name'=>'required|min:2|max:191',
            'ar_name'=>'required|min:2|max:191',
            'en_excerpt' => '',
            'ar_excerpt' => '',
            'created_by'=>'',
            'updated_by'=>'',
            'post_type'=>'required',
            'xero_code'=>'max:100',
        ];
    }
}
