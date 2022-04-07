<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class BrancheRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $file = 'required|';
        if ($this->method() == "PATCH"){
            $file = '';
        }
        return [
            'name'        =>'required|min:2|max:200',
            'title'       =>'required|min:2|max:200',
            'description' =>'',
            'main_color'  =>'',
            'expire_date' =>'',
            'timezone'    =>'',
            'active'      =>'',
            'file'        => $file.'mimes:jpeg,png,jpg,gif,svg|max:20480',
            'updated_by'  =>'',
        ];
    }
}
