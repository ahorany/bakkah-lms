<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class B2BRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'organization_id'=>'required',
            'session_id'=>'required',
            'training_option_id'=>'',
            'course_id'=>'required',
            'status_id'=>'',
            'owner_user_id'=>'',
            'created_by'=>'',
            'updated_by'=>'',
            'trashed_status'=>'',
            //
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
