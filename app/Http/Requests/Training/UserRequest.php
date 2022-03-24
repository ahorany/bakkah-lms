<?php

namespace App\Http\Requests\Training;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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

        $sql = "SELECT users.email ,users.password , users.gender_id ,users.mobile, user_branches.*   FROM users
                    LEFT JOIN user_branches ON users.id = user_branches.user_id
                    AND user_branches.branch_id = ".$current_user_branch->branch_id."
                    WHERE  users.email = '".\request()->email."'";



        $user_branch =  \DB::select($sql);

        \DB::select("SELECT * FROM model_has_roles
                    LEFT JOIN user_branches ON users.id = user_branches.user_id
                    AND user_branches.branch_id = ".$current_user_branch->branch_id."
                    WHERE  users.email = '".\request()->email."'");


        dd($user_branch);


        $email = '';
        $password = 'required|min:8|confirmed';
        if (isset($user_branch[0])){
            $password = '';
            if ($user_branch[0]->branch_id){
                $email = 'required|unique:users,email';
            }


//            if ($user_branch[0]->){
//                abort(404);
//            }
        }




//        $user_id = null; // when create
//        $password = 'required|min:8|confirmed'; // when create
//        if($this->getMethod() == 'PATCH'){ // when update
//            $password = 'nullable|min:8|confirmed';
//            $user_id = ','.$this->route('user')->id;
//        }

        return  [
            'name'   => 'min:2|max:100',
            'file'     => 'image|mimes:jpeg,png,jpg,svg|max:20480',
            'email'     => $email,
            'mobile'    => 'nullable|numeric|digits_between:2,15',
            'gender_id' => 'required|exists:constants,id',
            'password'  => $password,
            'role'      => 'required|exists:roles,id|not_in:4',
            'created_by'=> '',
            'updated_by'=> '',
        ];
    }
}
