<?php

namespace App\Http\Requests\Education;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UserRequest extends FormRequest
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
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });

        return [
            'en_name'=>'required|min:2|max:191|regex:/^[a-zA-Z ]+$/u',
            'ar_name'=>'',
            'created_by'=>'',
            'updated_by'=>'',
            'job_title'=>'max:191',
            'company'=>'max:191',
            'country_id'=>'required|exists:constants,id',
            'mail_subscribe'=>'numeric',
            'email'=>'required|email:rfc,dns|without_spaces',
            'mobile'=>'required|numeric|digits_between:8,15',
            'session_id'=>'required|exists:sessions,id',
            'gender_id'=>'required|exists:constants,id',
            'pp_agree'=>'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'en_name.required' => __('formerrors.The name field is required.'),
            'en_name.min' => __('formerrors.The name must be at least 2 characters.'),
            'en_name.max' => __('formerrors.The name may not be greater than 191 characters.'),
            'en_name.regex' => __('formerrors.Name Regex'),
            'job_title.max' => __('formerrors.The job title may not be greater than 191 characters.'),
            'company.max' => __('formerrors.The company may not be greater than 191 characters.'),
            'country_id.required' => __('formerrors.The country field is required.'),
            'country_id.exists' => __('formerrors.The selected country is invalid.'),
            'email.required' => __('formerrors.The email field is required.'),
            'email.email' => __('formerrors.The email must be a valid email address.'),
            'mobile.required' => __('formerrors.The mobile field is required.'),
            'mobile.numeric' => __('formerrors.The mobile must be a number.'),
            'mobile.digits_between' => __('formerrors.The mobile must be between 8 and 15 digits.'),
            'gender_id.required' => __('formerrors.The gender field is required.'),
            'gender_id.exists' => __('formerrors.The selected gender is invalid.'),
            'pp_agree.required' => __('formerrors.The agree field is required.'),
        ];
    }
}
