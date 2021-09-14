<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupInvoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type_id'=>'',//required
            'organization_id'=>'',//required
            'owner_user_id'=>'',
            'created_by'=>'',
            'updated_by'=>'',
            'en_title'=>'required|min:2|max:250',
            'ar_title'=>'max:250',
            'en_name'=>'required|min:2|max:250',
            'ar_name'=>'max:250',
            'email'=>'required|max:191|email:rfc,dns',
            'mobile'=>'required|numeric|digits_between:6,15',
            'job_title'=>'',
            'address'=>'',
            'course_id'=>'required',
            'session_id'=>'required',
            // 'reference'=>'max:150',
            // 'tax_number'=>'max:150',
            // 'accounting_sys_invoice'=>'max:50',
            // 'payment_date'=>'nullable|date',
            // 'payment_sentmail'=>'nullable|date',//
            // 'invoice_sent_date'=>'nullable|date',
            // 'due_date'=>'nullable|date',
            'follow_up_date'=>'nullable|date',
            // 'status_id'=>'required|exists:constants,id',
            // 'master_id'=>'required|exists:cart_masters,id',
            // 'invoice_amount'=>'numeric',
            // 'invoice_number'=>'max:50'
        ];
    }
}
