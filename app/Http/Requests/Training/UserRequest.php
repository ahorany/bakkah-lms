<?php

namespace App\Http\Requests\Training;

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
        $user_id = null; // when create
        $password = 'required|min:8|confirmed'; // when create
        if($this->getMethod() == 'PATCH'){ // when update
            $password = 'nullable|min:8|confirmed';
            $user_id = ','.$this->route('user')->id;
        }

        return  [
            'en_name'   => 'min:2|max:100',
            'ar_name'   => 'nullable|max:100',
            'file'     => 'image|mimes:jpeg,png,jpg,svg|max:20480',
            'email'     => 'unique:users,email'.$user_id.'|required',
            'mobile'    => 'nullable|numeric|digits_between:2,15',
            'gender_id' => 'required|exists:constants,id',
            'password'  => $password,
            'role'      => 'required|exists:roles,id',
            'created_by'=> '',
            'updated_by'=> '',
        ];
    }
}
