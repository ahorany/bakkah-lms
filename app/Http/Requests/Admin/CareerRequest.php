<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CareerRequest extends FormRequest
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
            'career_type_id'=>'required|exists:constants,id',
            'country_id'=>'required|exists:constants,id',
            'title'=>'required|max:191',
            'city'=>'required|max:100',
            'position_code'=>'required|max:100',
            'excerpt'=>'max:600',
            'details'=>'',
            'created_by'=>'',
            'updated_by'=>'',
            'slug'=>'max:191',
        ];
    }
}
