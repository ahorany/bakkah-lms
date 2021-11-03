<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

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
            'en_title'=>'required|min:2|max:250',
            'ar_title'=>'required|min:2|max:250',
            'en_excerpt'=>'max:1000',//required|min:2|
            'ar_excerpt'=>'max:1000',//required|min:2|
            'en_accredited_notes'=>'max:1000',
            'ar_accredited_notes'=>'max:1000',
            'en_disclaimer'=>'max:1000',
            'ar_disclaimer'=>'max:1000',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg,pdf|max:20480',
            'created_by'=>'',
            'updated_by'=>'',
            'slug'=>'',
            // 'slug'=>'required|min:2|max:500',
            'order'=>'nullable|numeric|max:1500',
//            'algolia_order'=>'nullable|numeric|max:1500',
            'en_short_title'=>'max:100',
            'ar_short_title'=>'max:100',
            'PDUs'=>'nullable|numeric',
            // 'price'=>'numeric',
            // 'exam_price'=>'nullable|numeric',
            // 'take2_price'=>'nullable|numeric',
            // 'take2_price_usd'=>'nullable|numeric',
            // 'exam_is_included'=>'',
            'rating'=>'nullable|numeric',
            'reviews'=>'nullable|numeric',
//            'xero_code'=>'max:100',
            // 'xero_exam_code'=>'max:100',
            // 'xero_exam_code_practitioner'=>'max:100',
            'material_cost'=>'nullable|numeric|max:1500',
            'partner_id'=>'',
            'certificate_type_id'=>'',
            'type_id'=>'',
            'show_in_website'=>'',
            'active'=>'',
            'group_id'=>'required|exists:groups,id',
            'training_option_id'=>'required|exists:constants,id',

        ];
    }
}
