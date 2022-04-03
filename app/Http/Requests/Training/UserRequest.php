<?php

namespace App\Http\Requests\Training;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        $current_user_branch =  getCurrentUserBranchData();
        $email      = '';
        $gender_id  = 'required|exists:constants,id';
        if($this->getMethod() == 'PATCH') { // when update
            $password   = 'nullable|min:8|confirmed';
        }else{ // when create
            $sql = "SELECT users.email ,users.password ,users.deleted_at as user_deleted_at , users.gender_id ,users.mobile, user_branches.*
                FROM users
                LEFT JOIN user_branches ON users.id = user_branches.user_id
                                        AND user_branches.branch_id = ".$current_user_branch->branch_id."
                WHERE  users.email = '".\request()->email."'";
            $user_branch =  \DB::select($sql);

            $password   = 'required|min:8|confirmed';
            if (isset($user_branch[0])){
                $password = '';
                $gender_id = '';
                if ($user_branch[0]->user_deleted_at){
                    $this->msg =  "This Account found in trash";
                    $email = 'required|unique:users,email';
                }
                if ($user_branch[0]->branch_id){
                    $email = 'required|unique:users,email';
                }
            }
        }



        return  [
            'name'   => 'min:2|max:100',
            'file'     => 'image|mimes:jpeg,png,jpg,svg|max:20480',
            'email'     => $email,
            'mobile'    => 'nullable|numeric|digits_between:2,15',
            'gender_id' => $gender_id,
            'password'  => $password,
            'role'   => ['required','not_in:4',
                Rule::exists('roles','id')
                    ->where('branch_id',getCurrentUserBranchData()->branch_id)
            ],
            'created_by'=> '',
            'updated_by'=> '',
        ];
    }


    public function messages()
    {
        if (isset($this->msg)){
            return array_merge(parent::messages(),['email.unique' => $this->msg]);
        }

       return parent::messages();

    }
}
