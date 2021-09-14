<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
            'en_excerpt'=>'max:1500',
            'ar_excerpt'=>'max:1500',
            'user_id'=>'required|exists:users,id',
            'course_id'=>'required|exists:courses,id',
            'show_in_home'=>'',
            'status'=>'',
            'order'=>'numeric|max:1500',
            'post_type'=>'',
            'post_date'=>'nullable|date',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg,pdf|max:20480',
            'created_by'=>'',
            'updated_by'=>'',
            'activated_by'=>'',
        ];
    }
}
