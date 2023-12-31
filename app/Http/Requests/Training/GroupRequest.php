<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
            'name'=>'required|min:2|max:200',
            'title'=>'required|min:2|max:200',
            'description'=>'',
            'expire_date'=>'',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'active'=>'',
            'updated_by'=>'',
        ];
    }
}
