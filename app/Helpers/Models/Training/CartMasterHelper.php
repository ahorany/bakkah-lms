<?php
namespace App\Helpers\Models\Training;

use App\Models\Training\CartMaster;

class CartMasterHelper {

    public function updateOrCreate($user_token){

        $cartMaster = CartMaster::firstOrCreate([
            'user_token'=>$user_token,
            'payment_status'=>63,
        ]);
        return $cartMaster;
    }

    public function GetMaster($master_id){

        $CartMaster = CartMaster::where('id', $master_id)->first();

        return $CartMaster;
    }

    public function updateTotal($id, $cart_total, $feature_total){

        $total = $cart_total->total + $feature_total->price;
        $vat_value = $cart_total->vat_value + $feature_total->vat_value;
        $total_after_vat = $cart_total->total_after_vat + $feature_total->total_after_vat;

        CartMaster::where('id', $id)->update([
            'total'=>$total,
            'vat_value'=>$vat_value,
            'total_after_vat'=>$total_after_vat,
        ]);
    }

    public function updateTotalAll($cart){

        $CartHelper = new CartHelper();

        $cart_total = $CartHelper->GetTotal($cart->master_id);
        $feature_total = $CartHelper->GetFeatureTotal($cart->id);
        $this->updateTotal($cart->master_id, $cart_total, $feature_total);
    }
}
