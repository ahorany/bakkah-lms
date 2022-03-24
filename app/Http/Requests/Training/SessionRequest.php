<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SessionRequest extends FormRequest
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
        $ref_id =  ['required', Rule::unique('sessions')->where('course_id',request()->course_id) ];

      if ($this->method() == "PATCH"){
          $ref_id =  ['required', Rule::unique('sessions')->where('course_id',request()->course_id)->ignore($this->route('session')->id, 'id') ];
      }

        return  [
            'date_from'       => ['required','date'],
            'date_to'         => ['required','date','after:date_from'],
            'course_id'       => ['required','exists:courses,id'],
            'ref_id'          => $ref_id,
        ];
    }
}
