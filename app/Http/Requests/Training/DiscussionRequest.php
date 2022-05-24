<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class DiscussionRequest extends FormRequest
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
            'title'       => "required|string|max:100",
            'course_id'   =>'required|exists:courses,id',
            'description' =>  "required|string",
            'start_date'  => 'required|date|before:end_date',
            'end_date'    => 'required|date|after:start_date',
        ];
    }
}
