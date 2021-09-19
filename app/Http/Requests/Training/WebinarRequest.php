<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class WebinarRequest extends FormRequest
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
            'en_excerpt'=>'required|min:2|max:1000',
            'ar_excerpt'=>'required|min:2|max:1000',
            'en_details'=>'',
            'ar_details'=>'',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg,pdf|max:20480',
            'created_by'=>'',
            'updated_by'=>'',
            'slug'=>'',
            'show_in_website'=>'',
            'zoom_link'=>'nullable|url|max:255',
            'video_link'=>'nullable|url|max:255',
            'session_start_time'=>'required',
            'session_end_time'=>'required',
            'order'=>'numeric|max:1500',
        ];
    }
}
