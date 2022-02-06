<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'en_title'=>'required|min:2|max:250',
            'ar_title'=>'required|min:2|max:250',
        ];
    }
}
