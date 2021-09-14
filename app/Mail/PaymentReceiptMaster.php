<?php

namespace App\Mail;

use App\Models\Training\Cart;
use App\Models\Training\CartMaster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PaymentReceiptMaster extends Mailable
{
    use Queueable, SerializesModels;

    public $cart_master_id;

    public function __construct($cart_master_id)
    {
        $this->cart_master_id = $cart_master_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $CartMast = CartMaster::findOrFail($this->cart_master_id);
        if(!is_null($CartMast) && $CartMast->type_id!=374){

            $cartMaster = CartMaster::with(['rfpGroup.organization', 'userId', 'carts.userId', 'carts.trainingOption', 'carts' => function($query) {
                $query->whereNull('trashed_status')
                      ->whereNull('deleted_at')
                      ->whereIn('payment_status', [68, 315, 317, 376, 332]);
            }])->findOrFail($this->cart_master_id);

        }else{

            $cartMaster = CartMaster::with(['carts.userId', 'carts.cartFeatures.feature', 'carts' => function($query) {
                $query->whereNull('trashed_status')
                      ->whereNull('deleted_at')
                      ->whereIn('payment_status', [68, 315, 317, 376, 332]);
            }])->findOrFail($this->cart_master_id);

        }

            $count = $cartMaster->carts->count()??0;

            // $cartFeatures  = DB::select(DB::raw("select sum(price) as total from cart_features where master_id in( select id from carts where master_id in($this->cart_master_id) )"));

            // $totalFeatures = $cartFeatures[0]->total??0;

            $totalPerStatus_query = "(sum(carts.total) - sum(carts.refund_value_before_vat)) as total, (sum(carts.vat_value) - sum(carts.refund_value_vat)) as vat_value, (sum(carts.total_after_vat) - sum(carts.refund_value_after_vat)) as total_after_vat";

            $masterTotals = CartMaster::join('carts', 'cart_masters.id', 'carts.master_id')
                                ->select(DB::Raw($totalPerStatus_query))
                                ->whereNull('carts.deleted_at')
                                ->whereNull('carts.trashed_status')
                                ->whereIn('carts.payment_status', [68, 315, 317, 376, 332])
                                ->find($this->cart_master_id);

            // if(!is_null($CartMast) && $CartMast->type_id==372){  // Group

                // $cartFeatures  = DB::select(DB::raw("select sum(price) as total from cart_features where master_id in( select id from carts where group_invoice_id in($this->cart_master_id) )"));

                // $totalFeatures = $cartFeatures[0]->total??0;

            //     $totalPerStatus_query = "(sum(carts.total) - sum(carts.refund_value_before_vat)) as total, (sum(carts.vat_value) - sum(carts.refund_value_vat)) as vat_value, (sum(carts.total_after_vat) - sum(carts.refund_value_after_vat)) as total_after_vat";

            //     $masterTotals = CartMaster::join('carts', 'cart_masters.id', 'carts.group_invoice_id')
            //                     ->select(DB::Raw($totalPerStatus_query))
            //                     ->whereNull('carts.deleted_at')
            //                     ->whereNull('carts.trashed_status')
            //                     ->whereIn('carts.payment_status', [68, 315, 317, 376, 332])
            //                     ->find($this->cart_master_id);
            // }

            // $coin_id = 'USD';
            // dd($cartMaster);
            // payment_receipt

            $coin_id = $cartMaster->coin_id??334;
            $invoice = [
                334=>'SAR',
                335=>'USD',
            ][$coin_id];

            return $this->subject('Payment Receipt')->markdown('emails.courses.payment_receipt.'.$invoice.'_master', ['cartMaster'=>$cartMaster,
            'count'=>$count,
            'masterTotals'=>$masterTotals,
            'user'=>$cartMaster->userId,
            'type_id'=>$CartMast->type_id
            ]);
    }
}
