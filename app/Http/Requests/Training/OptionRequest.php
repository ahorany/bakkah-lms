<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class OptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'en_title' => 'required|max:100',
            'ar_title' => 'required|max:100',
            'en_excerpt'=>'required|max:250',
            'ar_excerpt'=>'required|max:250',
        ];
    }
}
