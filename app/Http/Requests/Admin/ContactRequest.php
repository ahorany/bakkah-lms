<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name'=>'required|max:191',
            'email'=>'required|max:191|email:rfc,dns',
            'mobile'=>'required|numeric|digits_between:6,15',
            'request_type'=>'',
            'post_type'=>'',
            'message'=>'required',
            'created_by'=>'',
            'updated_by'=>'',
            'course_id'=>'',
        ];
    }
}
