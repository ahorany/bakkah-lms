<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title'=>'required|min:2|max:191',
            'post_date'=>'nullable|date',
            'date_to'=>'nullable|date',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg,pdf|max:20480',
            'post_type'=>'required',
            'details'=>'',
            'excerpt'=>'max:500',
            'created_by'=>'',
            'updated_by'=>'',
            'show_in_website'=>'',
            'slug'=>'max:191',
            'url'=>'max:255',
            'country_id'=>'nullable',
            'coin_id'=>'',
//            'order'=>'numeric|max:1500',
        ];
    }
}
