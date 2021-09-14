<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
            'en_title'=>'required|min:2|max:191',
            'ar_title'=>'required|min:2|max:191',
            'en_details'=>'required',
            'ar_details'=>'required',
            'created_by'=>'',
            'updated_by'=>'',
        ];
    }
}

