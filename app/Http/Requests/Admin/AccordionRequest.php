<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AccordionRequest extends FormRequest
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
            'en_title'=>'required|max:200',
            'ar_title'=>'required|max:200',
            'en_details'=>'',
            'ar_details'=>'',
            'master_id'=>'',
            'order'=>'',
            'created_by'=>'',
            'updated_by'=>'',
        ];
    }
}
