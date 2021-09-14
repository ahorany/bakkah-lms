<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'training_option_id'=>'required|exists:training_options,id',
//            'ar_excerpt'=>'required|min:2|max:127',
//            'duration_type'=>'',
//            'post_date'=>'nullable|date',
            'date_from'=>'',
            'date_to'=>'',
            'hours_per_day'=>'nullable|numeric',
            'duration'=>'nullable|numeric',
            'duration_type'=>'exists:constants,id',
            'retarget_discount'=>'',
            'session_time'=>'',
            'session_start_time'=>'required',
            'lang_id'=>'exists:constants,id',
            'vat'=>'',
            'price'=>'nullable|numeric',
            'exam_price'=>'nullable|numeric',
            'total'=>'nullable|numeric',
            'price_usd'=>'nullable|numeric',
            'exam_price_usd'=>'nullable|numeric',
            'total_usd'=>'nullable|numeric',
            'created_by'=>'',
            'updated_by'=>'',
            'show_price'=>'numeric',
            'money_back_guarantee'=>'',
            'send_reminder_before_start'=>'',
            'zoom_link'=>'nullable|url|max:255',
            'zoom_cost'=>'numeric',
            'trainer_id'=>'',
            'developer_id'=>'',
            'demand_team_id'=>'',
            'show_in_website'=>'',
            'attendance_count'=>'',
            'evaluation_api_code'=>'',
            'except_fri_sat'=>'',
            'type_id'=>'',
            'city_id'=>'',
//            'slug'=>'max:191',
//            'order'=>'numeric|max:1500',
//            'rep'=>'max:50',

        ];
    }
}
