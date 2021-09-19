<?php

namespace App\Http\Requests;

use App\ProfileAnswer;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $r = '';
        if(!is_null($this->answer)){
            if(ProfileAnswer::find($this->answer)->has_reason == 1 ){
                $r = "required";
             }
        }else{
            $r = "required";
        }



        return [
            "courses" => "required",
            "answer"    => "required",
            "reason"  => $r,
        ];
    }
}
