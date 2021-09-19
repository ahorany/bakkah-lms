<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RedirectRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'source_url' => 'required|url',
            'destination_url' => 'required|url',
            'constant_id' => 'required',
        ];
    }
}
