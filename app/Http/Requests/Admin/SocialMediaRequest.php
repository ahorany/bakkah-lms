<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'en_title' => 'required',
            // 'ar_title' => 'required',
            'description' => 'required',
            'constant_id' => 'required',
        ];
    }
}
