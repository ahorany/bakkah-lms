<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $validate_email_role = 'unique:users,email|required|email:rfc,dns|unique:organizations';
        if(request()->has('_method') && !is_null($this->organization_id)){
            $organization_id = request()->organization_id;
            $validate_email_role ='required|email:rfc,dns|unique:organizations,email, '.$this->organization_id;
        }

        return [
            'en_title' => 'required|max:100|string',
            'ar_title' => 'required|max:100|string',
            'email'=>$validate_email_role,
            'mobile'=>'required|numeric|digits_between:8,15',
            'en_name' => 'max:191',
            'ar_name' => 'max:191',
            'job_title' => 'max:191',
            'address' => 'max:191',
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
