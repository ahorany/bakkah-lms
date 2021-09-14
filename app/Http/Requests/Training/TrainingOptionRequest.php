<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class TrainingOptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id'=>'required|exists:courses,id',
            'constant_id'=>'required|exists:constants,id',
            'price'=>'numeric',
            'exam_price'=>'nullable|numeric',
            'price_usd'=>'numeric',
            'exam_price_usd'=>'nullable|numeric',
            'take2_price'=>'nullable|numeric',
            'take2_price_usd'=>'nullable|numeric',
            'exam_simulation_id' => '',
            // 'exam_simulation_price_sar'=>'nullable|numeric',
            // 'exam_simulation_price_usd'=>'nullable|numeric',
            'exam_is_included'=>'',
            'PDUs'=>'numeric',
            'en_details'=>'',
            'ar_details'=>'',
            'created_by'=>'',
            'updated_by'=>'',
            'en_this_course_includes'=>'',
            'ar_this_course_includes'=>'',
            'lms_id'=>'',
            'lms_course_id'=>'numeric',
            // 'evaluation_api_code'=>'',
        ];
    }
}
