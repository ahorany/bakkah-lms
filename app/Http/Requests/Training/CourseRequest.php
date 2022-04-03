<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
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
            'en_title'=>'required|string|min:2|max:250',
            'ar_title'=>'required|string|min:2|max:250',
            'en_excerpt'=>'string|max:1000',
            'ar_excerpt'=>'string|max:1000',
            'en_accredited_notes'=>'string|max:1000',
            'ar_accredited_notes'=>'string|max:1000',
            'en_disclaimer'=>'string|max:1000',
            'ar_disclaimer'=>'string|max:1000',
            'en_short_title'=>'string|max:100',
            'ar_short_title'=>'string|max:100',
            'PDUs'=>'nullable|numeric',
            'rating'=>'nullable|numeric',
            'reviews'=>'nullable|numeric',
            'order'=>'nullable|numeric',
            'complete_progress'=>'required|numeric',
            'code'=>'max:20',
            'training_option_id'=>'required|exists:constants,id',
            'certificate_id'=>'required|exists:certificates,id',
            'category_id'   => ['required',
                Rule::exists('categories','id')
                    ->where('branch_id',getCurrentUserBranchData()->branch_id)
            ],
            'file'=>'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'intro_video'=>'file|mimes:mp4|max:20480',
            'ref_id'=>'required|string|max:200',
            'slug'=>'',
            'created_by'=>'',
            'updated_by'=>'',
            'certificate_type_id'=>'',
        ];
    }
}
