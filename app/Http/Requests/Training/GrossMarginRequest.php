<?php

namespace App\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class GrossMarginRequest extends FormRequest
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
              "product_name"=> "required|numeric|exists:courses,id",
              "Session"=> "required|numeric|exists:sessions,id",
              "time"=> "required|numeric",
            //   "currency"=> "required|numeric",
              "total_hours"=> "nullable|numeric",
              "trainer_cost"=> "required|numeric",
              "developer_cost"=> "required|numeric",
              "demand_team_cost"=> "required|numeric",
              "zoom"=> "nullable|numeric",
              "trainees_no"=> "numeric",
              "attendants_no"=> "numeric",
         ];
    }
}
