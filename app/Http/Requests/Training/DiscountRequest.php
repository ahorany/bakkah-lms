<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class DiscountRequest extends FormRequest
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
            'ar_excerpt'=>'',
            'en_excerpt'=>'',
            'code'=>'required|max:50',
            // 'type_id'=>'required|exists:constants,id',
            // 'value'=>'numeric',//|regex:/^\d+(\.\d{1,2})?$/
            'start_date'=>'required',
            'end_date'=>'required',
            'created_by'=>'',
            'updated_by'=>'',
            // 'training_option_id'=>'',
            'country_id' => '',
            'is_private' => '',
            'candidates_no'=>'',
            'coin_id' => 'required|exists:constants,id',
        ];
    }
}
