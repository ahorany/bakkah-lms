<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'en_title'=>'required|min:2|max:250',
            'ar_title'=>'required|min:2|max:250',
            'en_excerpt'=>'required|min:2|max:1000',
            'ar_excerpt'=>'required|min:2|max:1000',
            'en_details'=>'',
            'ar_details'=>'',
            'show_in_website'=>'',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg,pdf|max:20480',
            'created_by'=>'',
            'updated_by'=>'',
            'slug'=>'',
            'order'=>'numeric|max:1500',
        ];
    }
}
