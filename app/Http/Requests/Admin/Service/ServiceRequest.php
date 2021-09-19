<?php

namespace App\Http\Requests\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            'en_excerpt'=>'max:250',
            'ar_excerpt'=>'max:250',
            'percentage'=>'numeric',
            'order'=>'numeric',
            'master_id'=>'',
            'created_by'=>'',
            'updated_by'=>'',
        ];
    }
}
