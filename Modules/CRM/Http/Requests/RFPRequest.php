<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RFPRequest extends FormRequest
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

        $args = [
            'en_title'=>'required|min:2|max:191',
            // 'en_title'=>'required|min:2|max:191|regex:/^[a-zA-Z ]+$/u',
            'ar_title'=>'',
            'created_by'=>'',
            'updated_by'=>'',
            'job_title'=>'max:191',
            'en_name'=>'required|min:2|max:191',
            // 'en_name'=>'required|min:2|max:191|regex:/^[a-zA-Z ]+$/u',
            'ar_name'=>'',
            'address'=>'max:191',
            'email'=>'required|email:rfc,dns|without_spaces',
            'mobile'=>'required|max:20',
            'course_id'=>'required|exists:courses,id',
            'session_id'=>'required|exists:sessions,id',
        ];

        if(is_null(request()->id)){
            $args = array_merge($args, [
                'excel_file'=> 'required|mimes:xlsx|max:2048',
               ]);
        }

        return $args;
    }

    public function messages()
    {
        $args_msg = [
            'en_title.required' => __('formerrors.The organization name field is required.'),
            'en_title.min' => __('formerrors.The organization name must be at least 2 characters.'),
            'en_title.max' => __('formerrors.The organization name may not be greater than 191 characters.'),
            'en_title.regex' => __('formerrors.Name Regex'),
            'en_name.required' => __('formerrors.The name field is required.'),
            'en_name.min' => __('formerrors.The name must be at least 2 characters.'),
            'en_name.max' => __('formerrors.The name may not be greater than 191 characters.'),
            'en_name.regex' => __('formerrors.Name Regex'),
            'email.required' => __('formerrors.The email field is required.'),
            'email.email' => __('formerrors.The email must be a valid email address.'),
            'mobile.required' => __('formerrors.The mobile field is required.'),
            'mobile.numeric' => __('formerrors.The mobile must be a number.'),
            // 'mobile.max' => __('formerrors.The mobile may not be greater than 20 characters.'),
            'job_title.max' => __('formerrors.The job title may not be greater than 191 characters.'),
            'address.max' => __('formerrors.The address may not be greater than 191 characters.'),
            'course_id.required' => __('formerrors.The product name field is required'),
            'course_id.exists' => __('formerrors.The product name field is required'),
            'session_id.required' => __('formerrors.The session field is required'),
            'session_id.exists' => __('formerrors.The session field is required'),
        ];

        if(is_null(request()->id)){
            $args_msg = array_merge($args_msg, [
                'excel_file.required' => __('formerrors.The excel file field is required.'),
            ]);
        }
        // dd($args_msg);
        return $args_msg;
    }
}
