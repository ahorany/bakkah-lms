<?php

namespace App\Http\Controllers\Front\Education;

use App\User;
use Carbon\Carbon;
use App\Mail\Invoice;
use App\Mail\SelfLms;
use App\Mail\MoodleLms;
use App\Mail\ThanksEmail;
use App\Mail\InvoiceMaster;
use App\Mail\PaymentReceipt;
use App\Models\Training\Cart;
use App\Models\Training\Payment;
use App\Mail\PaymentReceiptMaster;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Cache;
use App\Models\Training\CartMaster;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Helpers\RedirectRegisterPath;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Training\Discount\Discount;
use App\Events\NewTraineeHasRegisteredEvent;
use App\Helpers\Models\Training\SessionHelper;

class EpayController extends Controller
{
    public function cart_prepare_the_checkout($user_id, $total_after_vat, $master_id, $cartMasters)
    {
        if(is_null($user_id))
            return;

        // $cartMaster = CartMaster::with('userId')
        // ->where('user_id', $user_id)
        // // ->where('payment_status', '!=', 68)
        // ->where('payment_status', 63)
        // ->whereNull('trashed_status')
        // ->where('coin_id', session('coinID'))
        // ->first();

        // if(is_null($cartMaster)){
        //     return back();
        // }

        // if($master_id) {
        //     $cartMasters = CartMaster::with('userId')
        //     ->where('id', $master_id)
        //     ->where('user_id', $user_id)
        //     // ->where('payment_status', '!=', 68)
        //     ->where('payment_status', 63)
        //     ->where('type_id', 374)
        //     ->whereNull('trashed_status')
        //     ->where('coin_id', session('coinID'))
        //     ->get();
        // }else {
        //     $cartMasters = CartMaster::with('userId')
        //     ->where('user_id', $user_id)
        //     // ->where('payment_status', '!=', 68)
        //     ->where('payment_status', 63)
        //     ->where('type_id', 374)
        //     ->whereNull('trashed_status')
        //     ->where('coin_id', session('coinID'))
        //     ->get();
        // }

        // foreach($cartMasters as $cartMaster){
        //     dump($cartMaster);
        // }

        $url = env('payment_url').'/checkouts';
        $trasaction_id  = substr(md5(time()),0,20);

        // $paid_in = $cartMaster->invoice_amount??0;
        $paid_in = $total_after_vat??0;

        if(env('payment_mode')=='test'){
            $paid_in = (int)$paid_in;
        }
        else{
            $paid_in=number_format($paid_in, 2, '.', '');
        }

        $data = "entityId=" . env('payment_entityId') .
            "&amount=" . $paid_in .
            "&currency=SAR".
            "&paymentType=DB";//"&merchantInvoiceId=12" .

        ///////
        $data .= "&merchantTransactionId=" . $trasaction_id .
            "&merchantInvoiceId=". $cartMasters[0]->invoice_number . // chabge to master invoice number
            "&customer.givenName=". $cartMasters[0]->userId->username .
            "&customer.mobile=". $cartMasters[0]->userId->mobile .
            "&customer.email=". $cartMasters[0]->userId->email .
            "&customer.phone=". $cartMasters[0]->id;
//            "&customer.ip=". $customerIP .
            // "&customer.companyName=".$cartMaster->companyName;//]
        if(env('payment_mode')=='test'){
            $data .= "&testMode=EXTERNAL";
        }
        //&customer.surname=second
        //dd($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer '.env('payment_authorization')));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, env('SSL_VERIFYPEER'));// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        // $responseData = request();
        $responseData = json_decode($responseData, true);
        //dump($data);
        //dd($responseData);
        $id = $responseData['id']??-1;
        foreach ($cartMasters as $cartMaster) {
            $cartMaster->update([
                'transaction_id'=>$id,
            ]);
        }
        // dump($id);
        return $id;
    }

    private function UpdateCarts($cartMaster){

        CartMaster::where('id', $cartMaster->id)->update([
            'payment_status' => 68,
            'registered_at' => DateTimeNow(),
        ]);
        Cart::where('master_id', $cartMaster->id)
        ->where('payment_status', '!=', 68)
        ->whereNull('trashed_status')
        ->update([
            'payment_status' => 68,
            'registered_at' => DateTimeNow(),
        ]);
        // $cartMaster->update([
        //     'payment_status' => 68,
        //     'registered_at' => DateTimeNow(),
        // ]);

        // $cartMaster->carts()
        // ->where('master_id', $cartMaster->id)
        // ->where('payment_status', '!=', 68)
        // ->whereNull('trashed_status')
        // ->update([
        //     'payment_status'=>68,
        //     'registered_at'=>DateTimeNow(),
        // ]);
    }

    public function cartPayment(){


        if(isset($_GET["resourcePath"]) && !empty($_GET["resourcePath"])){
            $resourcePath = $_GET["resourcePath"];
            $after_explode = (explode("/",$resourcePath));
            $transaction_id = $after_explode[3];
        }
        if(!isset($transaction_id)) return;
        // get the carts items
        // $payments = Payment::where('transaction_id', $transaction_id)->get();
        $url = env('payment_url')."/checkouts/".$transaction_id."/payment";
        $url .= "?entityId=" . env('payment_entityId');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer '.env('payment_authorization')));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, env('SSL_VERIFYPEER'));// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        $responseData = json_decode($responseData, true);

        $code = $responseData['result']['code'];
        $description = $responseData['result']['description'];

        $payment_status = 63;
//    	$transaction = 'RJC';
        if (preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $responseData['result']['code'])
            || preg_match('/^(000\.400\.0|000\.400\.100)/', $responseData['result']['code'])) {
            // $transaction= 'ACK';
            //$user = User::find($payment->user_id);
            $payment_status = 68;
            $cartMasters = CartMaster::where('transaction_id', $transaction_id)
            ->where('total_after_vat', '!=', 0)
            ->get();
            foreach ($cartMasters as $cartMaster) {

                $total = GetCartTotalPriceAfterVat($cartMaster->id);
                // Create Payment
                Payment::updateOrCreate([
                        'master_id'=>$cartMaster->id,
                        'user_id' => $cartMaster->user_id??auth()->id(),
                    ],[
                        'paid_in'=>$total,
                        'transaction_id'=>$cartMaster->transaction_id,
                        'payment_status'=>$payment_status,
                        'description'=>$description,
                        'code'=>$code,
                        'paid_at'=>DateTimeNow(),
                ]);
                // dd($cartMaster);
                $this->UpdateCarts($cartMaster);

                $this->SendEmailMaster($cartMaster->userId, $cartMaster->id);
                app('App\Http\Controllers\Front\Education\LMSController')->run($cartMaster->id, null);

                // dd($cartMaster);
            }
            // foreach($payments as $payment) {
            //     Payment::where('id', $payment->id)
            //         ->update([
            //             'payment_status'=>$payment_status,
            //             'description'=>$description,
            //             'code'=>$code,
            //             'paid_at'=>DateTimeNow(),
            //         ]);

            //     //$payment = Payment::find($payment->id);
            //     $cart = $payment->cart;

            //     Cart::where('id', $cart->id)->update([
            //         'status_id'=>341,
            //         'master_id' => $master_id,
            //         'payment_status'=>$payment_status,
            //         'registered_at'=>DateTimeNow(),
            //     ]);

            //     $promo_code = $payment->cart->promo_code;
            //     if($promo_code) {
            //         $discountOBJ = Discount::where('code', $promo_code)->where('is_private', 1)->pluck('candidates_no');
            //         if($discountOBJ) {
            //             $candidates_no = $discountOBJ[0] - 1;
            //             Discount::where('code', $promo_code)->where('is_private', 1)->update([
            //                 'candidates_no' => $candidates_no
            //             ]);
            //         }
            //     }
            // }
                // New Hani - rollin the user in LMS for each product
            //app('App\Http\Controllers\Front\Education\LMSController')->run($cartMaster->id, null);
            //session()->forget('user_token');
            //$this->SendEmail($user, $cart);

        }else {
            // foreach($payments as $payment) {
            //     Payment::where('transaction_id', $transaction_id)
            //         ->update([
            //             'description'=>$description,
            //             'code'=>$code,
            //             'paid_at'=>DateTimeNow(),
            //         ]);
            // }
        }
        ////////////////////
        $statusArray = [
            68=>'success',
            63=>'fail',
            315=>'employee',
            316=>'refund',
            317=>'PO',
            332=>'free-seat',
        ][$payment_status];
        return redirect()->route('epay.cartPayment.thanks', [
            'code' => $code,
            'payment_status' => $payment_status,
            'description' => $description,
            'status' => $statusArray,
            // 'code'=>$code,
            // 'description'=>$description,
        ]);
    }

    function cartCheckout($user_id, $master_id = null) {

        $user = User::find($user_id);

        if($master_id) {
            $cartMasters = CartMaster::with('userId')
            ->where('id', $master_id)
            ->whereHas('carts', function(Builder $query){
                if(auth()->check()){
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'))->where('user_id', auth()->id());
                }else {
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'));
                }
            })
            ->with(['carts'=>function($query){
                if(auth()->check()){
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'))->where('user_id', auth()->id());
                }else {
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'));
                }
            }])
            ->where('user_id', $user_id)
            // ->where('payment_status', '!=', 68)
            ->where('payment_status', 63)
            ->where('type_id', 374)
            ->whereNull('trashed_status')
            ->where('coin_id', session('coinID'))
            // ->with(['cartFeatures.trainingOptionFeature.feature'])
            ->get();
        }else {
            $cartMasters = CartMaster::with('userId')
            ->whereHas('carts', function(Builder $query){
                if(auth()->check()){
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'))->where('user_id', auth()->id());
                }else {
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'));
                }
            })
            ->with(['carts'=>function($query){
                if(auth()->check()){
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'))->where('user_id', auth()->id());
                }else {
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'));
                }
            }])
            ->where('user_id', $user_id)
            //->where('payment_status', '!=', 68)
            ->where('payment_status', 63)
            ->where('type_id', 374)
            ->whereNull('trashed_status')
            ->where('coin_id', session('coinID'))
            // ->with(['cartFeatures.trainingOptionFeature.feature'])
            ->get();
        }

        // foreach($cartMasters as $cartMaster){
        //         dump($cartMaster);
        // }
        if(count($cartMasters)==0){
            return back();
        }
        $getFunction = 'hot-deals';
        if(!is_null($cartMasters))
        {
            $total_after_vat = 0;
            foreach($cartMasters as $cartMaster){
                $total_after_vat += GetCartTotalPriceAfterVat($cartMaster->id);
            }

            if($total_after_vat <= 0) {
                session()->put('code', '');
                session()->put('payment_status', 68);

                foreach ($cartMasters as $cartMaster) {
                    $payment_status = 332;
                    // if($cartMaster->retrieved_code) {
                    //     $payment_status = 68;
                    // }
                    CartMaster::where('id', $cartMaster->id)->update([
                        'payment_status' => $payment_status,
                        'registered_at' => DateTimeNow(),
                    ]);
                    Cart::where('master_id', $cartMaster->id)
                    ->where('payment_status', '!=', 68)
                    ->whereNull('trashed_status')
                    ->update([
                        'payment_status' => $payment_status,
                        'registered_at' => DateTimeNow(),
                    ]);
                }

                return redirect()->route('epay.payment.final_thanks', [
                    'status' => 'success'
                ]);
            }

            $transaction_id = $this->cart_prepare_the_checkout($user_id, $total_after_vat, $master_id, $cartMasters);
            // dd($transaction_id);
            return view(FRONT.'.education.products.cart-checkout', compact('transaction_id', 'cartMasters', 'user', 'total_after_vat'));
            // $Redirect = new RedirectRegisterPath($cart->id);
            // $getFunction = $Redirect->getFunction();
            // if($getFunction == 'checkout')
            // {
            //     $transaction_id = $this->prepare_the_checkout($cart);
            //     return view(FRONT.'.education.products.checkout', compact('transaction_id', 'cart'));
            // }
            // else if($getFunction == 'single'){
            //     return redirect()->route('education.courses.single', ['slug'=>$cart->course->slug,]);
            // }
            // else if($getFunction == 'retarget'){
            //     return redirect()->route('education.courses.register', [
            //         'slug'=>$cart->course->slug,
            //         'session_id'=>$cart->session_id,
            //         'promo'=>'discount',
            //     ]);
            // }
        }
        return redirect()->route('education.hot-deals');
    }

    // public function checkout($cart_id){
    //     $cart = Cart::find($cart_id);
    //     // $cart = Cart::HaveRegister($cart->session_id, $cart->user_id);
    //     if(!is_null($cart))
    //     {
    //         if(isset($cart->course->slug) && !is_null($cart->course->slug))
    //         {
    //             $SessionHelper = new SessionHelper();
    //             $single = $SessionHelper->SingleForCheckout($cart->course->slug)
    //             ->where('session_id', $cart->session_id)
    //             ->first();
    //             //Active Session
    //             if(!is_null($single))
    //             {
    //                 if(!is_null($single->discount_id))
    //                 {
    //                     //Discount is Not Active
    //                     if(NumberFormat($single->discount_value)!=NumberFormat($cart->discount)){
    //                         return redirect()->route('education.courses.single', ['slug'=>$cart->course->slug]);
    //                     }
    //                 }
    //                 $transaction_id = $this->prepare_the_checkout($cart);
    //                 return view(FRONT.'.education.products.checkout', compact('transaction_id', 'cart'));
    //             }
    //             //Note Active
    //             return redirect()->route('education.courses.single', ['slug'=>$cart->course->slug]);
    //         }
    //     }
    //     return redirect()->route('education.hot-deals');
    // }

    private function SendEmail($user, $cart){
        // $when = now()->addSeconds(5);
        Mail::to($user->email)->send(new Invoice($cart));
        Mail::to($user->email)
            ->cc(['accounting@bakkah.net.sa'])
            ->send(new PaymentReceipt($cart));//, 'mkassem@bakkah.net.sa'
        // Mail::to($user->email)->send(new Invoice($cart));
        // Mail::to($user->email)
        //     ->cc(['accounting@bakkah.net.sa', 'mkassem@bakkah.net.sa'])
        //     ->send(new PaymentReceipt($cart));

        // Need to have $master_id
        // event(new NewTraineeHasRegisteredEvent($cart));
        // app('App\Http\Controllers\Front\Education\LMSController')->run($cart);
        //app('App\Http\Controllers\Front\Education\LMSController')->run($master_id, $cart->id);
    }

    public function SendEmailMaster($user, $master_id){

        // $when = now()->addSeconds(5);

        //Orders, B2B, GI, RFQ
        Mail::to($user->email)->send(new InvoiceMaster($master_id));
        Mail::to($user->email)
            ->cc(['accounting@bakkah.net.sa'])
            ->send(new PaymentReceiptMaster($master_id));//, 'mkassem@bakkah.net.sa'
        // Mail::to($user->email)->send(new Invoice($cart));
        // Mail::to($user->email)
        //     ->cc(['accounting@bakkah.net.sa', 'mkassem@bakkah.net.sa'])
        //     ->send(new PaymentReceipt($cart));

        // event(new NewTraineeHasRegisteredEvent($cart));
        // app('App\Http\Controllers\Front\Education\LMSController')->run($cart);
        // app('App\Http\Controllers\Front\Education\LMSController')->run($master_id, null);
    }

    public function paypal(){

        // \Artisan::call('queue:work');
        // return;
        $cart_id = request()->cart_id;
        $paid_in = request()->paid_in;
        $cart = Cart::find($cart_id);

        session()->forget('code');
        session()->forget('payment_status');
        session()->forget('description');
        session()->put('code', 'paypal');
        session()->put('payment_status', 68);

        $payment = Payment::updateOrCreate([
            'master_id'=>$cart->id,
            'user_id'=>$cart->userId->id,
        ],[
            'paid_in'=>$paid_in,
            'payment_status'=>68,
            'code'=>'paypal',
            // 'transaction_id'=>$id,
            'paid_at'=>DateTimeNow(),
        ]);
        Cart::where('id', $cart_id)->update(['payment_status'=>68, 'registered_at'=>DateTimeNow(),]);

        // $this->SendEmail($cart->userId, $cart);
        $this->SendEmailMaster($cart->userId, $cart);

        // return;
        return redirect()->route('epay.payment.thanks', [
            'payment'=>$payment,
            'status'=>'success',
        ]);
    }

    public function cartPaypal(){

        // \Artisan::call('queue:work');
        // return;
        $user_id = request()->user_id;
        $paid_in = request()->paid_in;

        $cartMasters = CartMaster::with('userId')
        ->where('total_after_vat', '!=', 0)
        ->where('user_id', $user_id)
        ->where('payment_status', '!=', 68)
        ->whereNull('trashed_status')
        ->where('coin_id', session('coinID'))
        ->get();

        foreach ($cartMasters as $cartMaster) {

            $total = GetCartTotalPriceAfterVat($cartMaster->id);
            // Create Payment
            $payment = Payment::updateOrCreate([
                    'master_id'=>$cartMaster->id,
                    'user_id' => $cartMaster->user_id??auth()->id()
                ],[
                    'paid_in'=>$total,
                    //'transaction_id'=>$transaction_id,
                    'payment_status'=> 68,
                    //'description' => $description,
                    'code'=> 'paypal',
                    'paid_at'=>DateTimeNow(),
            ]);
            $this->UpdateCarts($cartMaster);

            $this->SendEmailMaster($cartMaster->userId, $cartMaster->id);
            app('App\Http\Controllers\Front\Education\LMSController')->run($cartMaster->id, null);
            // dd($cartMaster);
        }

        // $carts = Cart::whereIn('user_id', [Auth::id()])
        // ->where('payment_status', '!=', 68)
        // ->whereNull('trashed_status')
        // ->where('coin_id', session('coinID'))
        // ->with(['cartFeatures.trainingOptionFeature.feature'])
        // ->get();

        session()->forget('code');
        session()->forget('payment_status');
        session()->forget('description');
        session()->put('code', 'paypal');
        session()->put('payment_status', 68);


        // foreach($carts as $cart) {
        //     $payment = Payment::updateOrCreate([
        //         'master_id'=>$cart->id,
        //         'user_id'=>$cart->userId->id,
        //     ],[
        //         'paid_in'=>$cart->total_after_vat,
        //         'payment_status'=>68,
        //         'code'=>'paypal',
        //         // 'transaction_id'=>$id,
        //         'paid_at'=>DateTimeNow(),
        //     ]);
        //     Cart::where('id', $cart->id)->update(['payment_status'=>68]);
        // }


        // return;
        return redirect()->route('epay.payment.thanks', [
            'payment'=>$payment,
            'status'=>'success',
        ]);
    }

    public function thanks(Payment $payment, $status='success'){

        session()->forget('code');
        session()->forget('payment_status');
        session()->forget('description');
        session()->put('code', $payment->code);
        session()->put('payment_status', $payment->payment_status);
        session()->put('description', $payment->description);
        return redirect()->route('epay.payment.final_thanks', [
            'status'=>$status,
        ]);
        // return view(FRONT.'.education.courses.register.thanks', compact('payment'));
    }

    public function cartThanks($code, $payment_status, $description, $status='success'){

        session()->forget('code');
        session()->forget('payment_status');
        session()->forget('description');
        session()->put('code', $code);
        session()->put('payment_status', $payment_status);
        session()->put('description', $description);
        return redirect()->route('epay.payment.final_thanks', [
            'status'=>$status,
        ]);
        // return view(FRONT.'.education.courses.register.thanks', compact('payment'));
    }

    public function final_thanks($status='success'){
        if(session()->has('code')){
            return view(FRONT.'.education.products.register.thanks');
        }
        return redirect()->route('education.index');
    }
}
