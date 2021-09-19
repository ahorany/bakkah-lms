<?php

namespace App\Helpers\Controllers;

use App\User;
use App\Models\Training\Cart;
use App\Models\Training\CartMaster;
use App\Models\Training\CartFeature;
use App\Models\Training\TrainingOptionFeature;

class RegisterHelper {

    public function UserValidated($validated)
    {
        $validated['name'] = null;
        $validated['user_type'] = 41;
        unset($validated['session_id']);
        unset($validated['pp_agree']);
        return $validated;
    }

    public function UpdateOrCreate($user, $session)
    {
        $org_id = null;
        if(session()->has('org_id'))
            $org_id = session()->get('org_id')??null;

        $discount_id = request()->DiscountId??null;
        $price = request()->price??0;
        $take2_price = request()->Take2Price??0;
        $exam_simulation_price = request()->ExamSimulationPrice??0;
        $pract_exam_price = request()->pract_exam_price??0;
        $book_price = request()->book_price??0;
        $exam_price = request()->ExamPrice??0;
        $Discount = request()->Discount??0;
        $DiscountValue = request()->DiscountValue??0;
        $promo_code = request()->promo_code??null;
        $subtotal = request()->subtotal??0;
        $VAT = request()->VAT??0;
        $VATVALUE = request()->VATVALUE??0;
        $PriceAfterDiscountWithExamPriceAfterVAT = request()->PriceAfterDiscountWithExamPriceAfterVAT??0;
        // $retrieved_code = request()->retrieved_code;
        // $Balance = request()->Balance;
        // $PaymentRemaining = request()->PaymentRemaining;
        // $Remaining = request()->Remaining;
        // $ValidRetrievedCode = request()->ValidRetrievedCode;

        // $retrieved_value = $Remaining > 0 ? NumberFormat($PriceAfterDiscountWithExamPriceAfterVAT) : NumberFormat($PriceAfterDiscountWithExamPriceAfterVAT) - $PaymentRemaining;

        // if($ValidRetrievedCode == 'no') {
        //     $retrieved_code = null;
        //     $retrieved_value = 0;
        // }

        $coin_id = GetCoinId();
        $coin_price = GetCoinPrice();

        $cartMaster = CartMaster::updateOrCreate([
            'user_id'=> $user->id,
            'session_id' => $session->id,
            'payment_status' => 63,
        ], [
            'total' => NumberFormat($subtotal),
            'vat' => NumberFormat($VAT),
            'vat_value' => NumberFormat($VATVALUE),
            'total_after_vat' => NumberFormat($PriceAfterDiscountWithExamPriceAfterVAT),
            'invoice_amount' => NumberFormat($PriceAfterDiscountWithExamPriceAfterVAT),
            'invoice_number' => date("His").rand(1234, 9632),
            'type_id' => 374,
            // 'retrieved_value' => $retrieved_value,
            // 'retrieved_code' => $retrieved_code,
            'coin_id' => $coin_id,
            'coin_price' => $coin_price,
        ]);

        $cart = Cart::updateOrCreate([
            'session_id' => $session->id,
            'user_id' => $user->id,
            'master_id' => $cartMaster->id,
            'register_type' => 'guest',
            'payment_status'=>63,
        ],[
            'status_id' => 327,//51
            'price' => NumberFormat($price),
            'discount_id' => $discount_id,
            'discount' => NumberFormat($Discount),
            'discount_value' => NumberFormat($DiscountValue),
            'promo_code' => $promo_code,
            // 'retrieved_value' => $retrieved_value,
            // 'retrieved_code' => $retrieved_code,
            'exam_price' => NumberFormat($exam_price),
            'take2_price' => NumberFormat($take2_price),
            'exam_simulation_price' => NumberFormat($exam_simulation_price),
            //
            'pract_exam_price' => NumberFormat($pract_exam_price),
            'book_price' => NumberFormat($book_price),
            'total' => NumberFormat($subtotal),
            'vat' => NumberFormat($VAT),
            'vat_value' => NumberFormat($VATVALUE),
            'total_after_vat' => NumberFormat($PriceAfterDiscountWithExamPriceAfterVAT),
            'training_option_id' => $session->training_option_id,//??
            'course_id' => $session->trainingOption->course_id,//??
            'invoice_number' => date("His").rand(1234, 9632),//??
            'trying_count'=>1,
            'coin_id'=>$coin_id,
            'coin_price'=>$coin_price,
            'registered_at'=>DateTimeNow(),
            'locale'=>app()->getLocale(),
            'org_id'=>$org_id,
        ]);


        // if($ValidRetrievedCode == 'yes') {
        //     User::where('id', $user->id)->update(['balance' => $Remaining]);
        // }

        $feature__delete = [];
        $feature__delete = $this->TrainingOptionFeatures($cart, $feature__delete);
        $feature__delete = $this->TrainingOptionFeatures_IsIncluded($cart, $feature__delete);

        $this->DeleteFeature($cart, $feature__delete);
        $this->UpdateCartFeature($cart);
        // app('App\Http\Controllers\Front\Education\LMSController')->run($master_id, $cart->id);
        return [
            'cartMaster'=>$cartMaster,
            'cart'=>$cart,
        ];
    }

    public function TrainingOptionFeatures($cart, $feature__delete)
    {
        if(request()->trainingOptionFeatures)
        {
            foreach (request()->trainingOptionFeatures as $id => $price)
            {
                array_push($feature__delete, $id);
                $this->AddCartFeatureItem($cart, $price, $id, null);
            }
        }
        return $feature__delete;
    }

    public function TrainingOptionFeatures_IsIncluded($cart, $feature__delete)
    {
        $includeTrainingOptionFeatures = TrainingOptionFeature::where('training_option_id', $cart->training_option_id)
        ->where('is_include', 1)
        ->get();
        foreach($includeTrainingOptionFeatures as $trainingOptionFeature)
        {
            array_push($feature__delete, $trainingOptionFeature->id);
            $this->AddCartFeatureItem($cart, $trainingOptionFeature->price, $trainingOptionFeature->id, 1);
        }
        return $feature__delete;
    }

    public function AddCartFeatureItem($cart, $price, $training_option_feature, $is_include=null)
    {
        $vat = 0;
        $vat_value = 0;
        $total_after_vat = $price;
        if(GetCoinId() == 334) {
            $vat = $cart->vat;
            $vat_value = $price * ($vat / 100);
            $total_after_vat = $price + $vat_value;
        }

        CartFeature::UpdateOrCreate([
            'master_id' => $cart->id,
            'training_option_feature_id' => $training_option_feature,
        ],[
            'user_id' => $cart->user_id,
            'price' => $price,
            'vat' => $vat,
            'vat_value' => $vat_value,
            'total_after_vat' => $total_after_vat,
            'is_include' => $is_include,
        ]);
    }

    public function UpdateCartFeature($cart)
    {
        $cartFeatures = CartFeature::where('master_id', $cart->id)->get();
        $exam_price = 0;
        $take2_price = 0;
        $exam_simulation_price = 0;
        $pract_exam_price = 0;
        $book_price = 0;
        foreach($cartFeatures as $cartFeature) {
            if($cartFeature->trainingOptionFeature->feature_id == 1){
                $exam_price = $cartFeature->price;
            }
            else if($cartFeature->trainingOptionFeature->feature_id == 2){
                $exam_simulation_price = $cartFeature->price;
            }
            else if($cartFeature->trainingOptionFeature->feature_id == 3){
                $take2_price = $cartFeature->price;
            }
            else if($cartFeature->trainingOptionFeature->feature_id == 4){
                $pract_exam_price = $cartFeature->price;
            }
            else if($cartFeature->trainingOptionFeature->feature_id == 5){
                $book_price = $cartFeature->price;
            }
        }
        Cart::where('id', $cart->id)->update([
            'exam_price'=>$exam_price,
            'take2_price'=>$take2_price,
            'exam_simulation_price'=>$exam_simulation_price,
            'pract_exam_price'=>$pract_exam_price,
            'book_price'=>$book_price,
        ]);
    }

    private function DeleteFeature($cart, $feature__delete)
    {
        CartFeature::where('master_id', $cart->id)
            ->whereNotIn('training_option_feature_id', $feature__delete)->forceDelete();
    }
}
