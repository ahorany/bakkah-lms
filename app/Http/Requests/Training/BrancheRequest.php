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
        // dd($this->branch->id);
        return [
            'name'=>'required|min:2|max:200',
            'title'=>'required|min:2|max:200',
            'description'=>'',
            'main_color'=>'',
            'expire_date'=>'',
            'timezone'=>'',
            'active'=>'',
            'file'=>'requiredOnCreate|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'updated_by'=>'',
        ];
    }
}
