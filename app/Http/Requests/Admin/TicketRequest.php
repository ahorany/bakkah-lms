<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->_method){
            return [
                'status' => '',
                'title' =>'',
                'description' => '',
                'ticket_file' => '',
                'priority' => '',
                'issue' => '',
                'company' => '',

            ]; // update
        }

        // store
        return [
            'description' => 'required',
            'title' => 'required',
            'ticket_file' => '',
            'status' => '',
            'priority' => 'required',
            'issue' => 'required',
            'company' => 'required',
        ];
    }
}
