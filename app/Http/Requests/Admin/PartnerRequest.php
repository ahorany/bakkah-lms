<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
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
            'en_name'=>'required|min:2|max:127',
            'ar_name'=>'required|min:2|max:127',
            'en_excerpt'=>'max:250',
            'ar_excerpt'=>'max:250',
            'en_details'=>'',
            'ar_details'=>'',
            'post_date'=>'nullable|date',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg,pdf|max:20480',
            'post_type'=>'required',
            'created_by'=>'',
            'updated_by'=>'',
            'slug'=>'max:191',
            'order'=>'numeric|max:1500',
            'rep'=>'max:50',
            'show_in_home'=>'',
            'in_education'=>'',
            'in_consulting'=>'',
            'xero_code'=>'max:100',
        ];
    }
}
