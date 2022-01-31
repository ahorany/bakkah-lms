<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $user_id = null;
        if(request()->has('user_id')){
            $user_id = ','.request()->user_id;
        }



        $course_id = 'required';

         if($this->getMethod() == 'PATCH'){
            $course_id = '';
         }


        $args = [
            'en_name'=>'min:2|max:191',//|regex:/^[A-Za-z]*$/
            // 'ar_name'=>'min:2|max:191',
            'ar_name'=>'nullable|max:191',
            'image'=>'image|mimes:jpeg,png,jpg,gif,svg,pdf|max:20480',
            'created_by'=>'',
            'updated_by'=>'',
            'job_title'=>'',
            'company'=>'',
            'country'=>'',
            'country_id' => '',
            'mail_subscribe'=>'',
            'email'=>'unique:users,email'.$user_id.'|required',
            'mobile'=>'max:20',
            'user_type' => 'exists:constants,id',
            'gender_id' => '',
            'trainer_courses_for_certifications'=>'max:500',
            'password'=>'nullable|min:8|confirmed',
            'role' => 'required',
            'course_id' => $course_id,
//            'group_id' => '',
        ];
        // if(request('_method')!='PATCH'){
        //     $args = array_merge($args, [
        //         'password'=>'nullable|min:8|confirmed',
        //     ]);
        // }

        return $args;
    }
}
