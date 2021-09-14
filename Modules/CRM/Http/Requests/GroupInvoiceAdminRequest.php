<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupInvoiceAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'type_id'=>'required',//required
            'organization_id'=>'required|exists:organizations,id',//required
            'owner_user_id'=>'required|exists:users,id',
            // 'created_by'=>'',
            // 'updated_by'=>'',
            'course_id'=>'required',
            'session_id'=>'required',
            // 'reference'=>'nullable|max:150',
            // 'tax_number'=>'nullable|max:150',
            // 'accounting_sys_invoice'=>'nullable|max:50',
            // 'payment_date'=>'nullable|date',
            // 'payment_sentmail'=>'nullable|date',//
            // 'invoice_sent_date'=>'nullable|date',
            // 'due_date'=>'nullable|date',
            'follow_up_date'=>'nullable|date',
            // 'status_id'=>'required|exists:constants,id',
            // 'master_id'=>'required|exists:cart_masters,id',
            // 'invoice_amount'=>'nullable|numeric',
            // 'invoice_number'=>'nullable|max:50'
            'price'=>'required',
            'discount_id'=>'',
            'discount'=>'',
            'discount_value'=>'',
            'promo_code'=>'',
            'exam_price'=>'',
            'take2_price'=>'',
            'exam_simulation_price'=>'',
            'total'=>'',
            'vat'=>'',
            'vat_value'=>'',
            'total_after_vat'=>'',
            'coin_id'=>'',
            'coin_price'=>'',
        ];
    }
}
