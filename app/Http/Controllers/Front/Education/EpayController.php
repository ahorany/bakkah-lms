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
use App\Mail\PaymentReceiptMaster;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Training\CartMaster;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Helpers\RedirectRegisterPath;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Training\Discount\Discount;
use App\Events\NewTraineeHasRegisteredEvent;
use App\Helpers\Models\Training\SessionHelper;
use App\MadaCard;
use Checkout\CheckoutApi;
use Checkout\Models\Tokens\Card;
use Checkout\Models\Payments\TokenSource;
use Checkout\Models\Payments\Payment as CheckoutPayment;
use App\Models\Training\Payment as PaymentTable;
use Checkout\Models\Payments\PaypalSource;

class EpayController extends Controller
{
    public function cart_prepare_the_checkout($user_id, $total_after_vat, $master_id, $cartMasters)
    {
        if(is_null($user_id))
            return;

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

    // ================= Start of Payment Section ==========================

    public function cartPayment($user_id, $master_id = null){

        // dd(request());
        // return request();

        $currency = GetCoinId()==335?'USD':'SAR';
        $user = User::find($user_id);
        $user_name = json_decode($user->name)->en??'';
        // get the cart master
        $cartMasters = $this->getCartMaster($user_id, $master_id);

        if(count($cartMasters)==0){
            return back();
        }


        if(!is_null($cartMasters))
        {
            $total_after_vat = 0;
            $items = [];
            $items['id'] = [];
            $items['invoice_number'] = [];
            $items['course'] = [];
            // $items['features'] = [];
            // $items['carts'] = [];
            foreach($cartMasters as $cartMaster){
                // $items['id'] = $cartMaster->id;
                array_push($items['id'], $cartMaster->id);
                array_push($items['invoice_number'], $cartMaster->invoice_number);

                if(!is_null($cartMaster->carts)){
                    foreach($cartMaster->carts as $cart){

                        $features = [];
                        if(!is_null($cart->cartFeatures)){
                            foreach($cart->cartFeatures as $feature){
                                array_push($features, $feature->trainingOptionFeature->feature->en_title.' | '.NumberFormatWithComma($feature->price));
                            }
                        }

                        $course_desc = [
                            'session'=>$cart->course->en_short_title.' | '.$cart->trainingOption->constant->en_name.' | '.NumberFormatWithComma($cart->price-$cart->discount_value).' '.$currency.' | '.$cart->session->date_from,
                            'features'=>$features,
                        ];
                        array_push($items['course'], $course_desc);
                    }
                }

                $total_after_vat += GetCartTotalPriceAfterVat($cartMaster->id);
            }
            // $items = (json_encode($items));

            // dump($items['course']);
            // dd($items['features']);
            // return false;


            session()->forget('zeroPaid');
            session()->forget('code');
            session()->forget('payment_status');
            session()->put('payment_status', 63);
// dd($total_after_vat);
            // if Free
            if($total_after_vat <= 0) {
                session()->put('zeroPaid', true);
                session()->forget('payment_status');
                session()->put('payment_status', 332);
                $payment_status = 332;

                foreach ($cartMasters as $cartMaster) {

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

                    $this->SendEmailMaster($cartMaster->userId, $cartMaster->id);
                    app('App\Http\Controllers\Front\Education\LMSController')->run($cartMaster->id, null);
                }

                return redirect()->route('epay.payment.final_thanks', [
                    'status' => 'success'
                ]);
            }

        }

        $ids = str_replace('"',"'", json_encode($items['id']));
        $invs = str_replace('"',"'", json_encode($items['invoice_number']));
        $courses = str_replace('"',"'", json_encode($items['course']));

        // -------------------- Credit Cards -----------------------
        $secretKey = env('CHECHOUT_SECRET_KEY');
        $token = request()->token;
        $bin = request()->bin;

        $checkout = new CheckoutApi($secretKey, false);
        $method = new TokenSource($token);

        // Prepare the payment parameters
        $amount = $total_after_vat * 100;
        $payment = new CheckoutPayment($method, $currency);

        $payment->amount = $amount;
        $payment->reference = $invs;
        $payment->customer['name'] = $user_name;
        $payment->customer['email'] = $user->email??'';

        $payment->metadata = [
            'cartMasterId' => $ids,
            'invoiceNumber' => $invs,
            'courses' => $courses,
            // 'items' => json_encode($items),
        ];

        $is_mada = 0;
        $is_mada = MadaCard::where('bin', 'like', '%'.$bin.'%')->count();
        if($is_mada > 0){
            $ds = '3ds';
            $payment->$ds = ['enabled'=>true];
            $payment->metadata = array_merge($payment->metadata, ['udf1' => 'mada']);
        }

        $payment->success_url = "https://testing.bakkah.net.sa/epay/sucsess/thanks";
        $payment->failure_url = "https://testing.bakkah.net.sa/epay/fail/thanks";

        // dd($payment);

        // Send the request and retrieve the response
        $response = $checkout->payments()->request($payment);

        // dd($response);
        // $details = $checkout->payments()->details($response->id);
        foreach ($cartMasters as $cartMaster) {
            CartMaster::where('id', $cartMaster->id)->update([
                'payment_rep_id' => $response->id,
            ]);
        }

        $status = 'fail';
        session()->forget('code');
        session()->forget('zeroPaid');
        session()->forget('payment_status');
        session()->put('payment_status', 63);
        session()->put('code', $status);


        if($is_mada > 0){

            if($response->status == 'Pending'){
                $status = 'sucsess';
                session()->forget('payment_rep_id');
                session()->forget('payment_type');
                session()->forget('code');
                session()->forget('payment_status');
                session()->forget('total');
                // session()->forget('description');

                session()->put('payment_rep_id', $response->id);
                session()->put('payment_type', 'PayPal');
                session()->put('code', 'Pending');
                session()->put('payment_status', 63);
                session()->put('total', $total_after_vat);
                // session()->put('description', 'There is something went failed.');

                $url = $response->_links['redirect']['href'];

                return compact('url');
                // return redirect()->away($url);
            }

        }else{

            if($response->status == 'Authorized'){

                $status = 'sucsess';
                session()->forget('payment_rep_id');
                session()->forget('payment_type');
                session()->forget('code');
                session()->forget('payment_status');
                session()->forget('total');
                // session()->forget('description');

                session()->put('payment_rep_id', $response->id);
                session()->put('payment_type', 'CreditCard');
                session()->put('code', $status);
                session()->put('payment_status', 63);
                session()->put('total', $total_after_vat);
                // session()->put('description', 'There is something went failed.');

            }

        }

        return response()->json([
            'url' => route('epay.payment.final_thanks', ['status'=>$status])
        ]);

    }

    public function paypalByCheckout($user_id, $master_id = null){

        // return request();
        $currency = GetCoinId()==335?'USD':'SAR';
        $user = User::find($user_id);
        $user_name = json_decode($user->name)->en??'';
        // get the cart master
        $cartMasters = $this->getCartMaster($user_id, $master_id);

        if(count($cartMasters)==0){
            return back();
        }

        if(!is_null($cartMasters))
        {
            $total_after_vat = 0;
            $items = [];
            $items['id'] = [];
            $items['invoice_number'] = [];
            $items['course'] = [];
            // $items['features'] = [];
            // $items['carts'] = [];
            foreach($cartMasters as $cartMaster){
                // $items['id'] = $cartMaster->id;
                array_push($items['id'], $cartMaster->id);
                array_push($items['invoice_number'], $cartMaster->invoice_number);

                if(!is_null($cartMaster->carts)){
                    foreach($cartMaster->carts as $cart){

                        $features = [];
                        if(!is_null($cart->cartFeatures)){
                            foreach($cart->cartFeatures as $feature){
                                array_push($features, $feature->trainingOptionFeature->feature->en_title.' | '.NumberFormatWithComma($feature->price));
                            }
                        }

                        $course_desc = [
                            'session'=>$cart->course->en_short_title.' | '.$cart->trainingOption->constant->en_name.' | '.NumberFormatWithComma($cart->price-$cart->discount_value).' '.$currency.' | '.$cart->session->date_from,
                            'features'=>$features,
                        ];
                        array_push($items['course'], $course_desc);
                    }
                }

                $total_after_vat += GetCartTotalPriceAfterVat($cartMaster->id);
            }
            // $items = (json_encode($items));

            // dump($items['course']);
            // dd($items['features']);
            // return false;

            session()->forget('zeroPaid');
            session()->forget('code');
            session()->forget('payment_status');
            session()->put('payment_status', 63);

            // if Free
            if($total_after_vat <= 0) {
                session()->put('zeroPaid', true);
                session()->forget('payment_status');
                session()->put('payment_status', 332);
                $payment_status = 332;

                foreach ($cartMasters as $cartMaster) {

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

                    $this->SendEmailMaster($cartMaster->userId, $cartMaster->id);
                    app('App\Http\Controllers\Front\Education\LMSController')->run($cartMaster->id, null);

                }

                return redirect()->route('epay.payment.final_thanks', [
                    'status' => 'success'
                ]);
            }

        }

        $ids = str_replace('"',"'", json_encode($items['id']));
        $invs = str_replace('"',"'", json_encode($items['invoice_number']));
        $courses = str_replace('"',"'", json_encode($items['course']));

        // -------------------- PayPal -----------------------
        $secretKey = env('CHECHOUT_SECRET_KEY');
        $checkout = new CheckoutApi($secretKey, false);

        // PayPal
        $invoice_no = $items['invoice_number'][0];
        $amount = $total_after_vat * 100;
        $payment = new CheckoutPayment(new PaypalSource($invoice_no), $currency);
        // $payment = new CheckoutPayment(new PaypalSource($invoice_no), $currency, $amount);

        $payment->amount = $amount;
        $payment->reference = $invs; //1000;
        $payment->customer['name'] = $user_name;
        $payment->customer['email'] = $user->email??'';

        $payment->metadata = [
            'cartMasterId' => $ids,
            'invoiceNumber' => $invs,
            'courses' => $courses,
            // 'items' => json_encode($items),
        ];

        $payment->success_url = "https://testing.bakkah.net.sa/epay/sucsess/thanks";
        $payment->failure_url = "https://testing.bakkah.net.sa/epay/fail/thanks";

        // dd($payment);

        $response = $checkout->payments()->request($payment);
        // dd($response);
        // $details = $checkout->payments()->details($response->id);
        foreach ($cartMasters as $cartMaster) {
            CartMaster::where('id', $cartMaster->id)->update([
                'payment_rep_id' => $response->id,
            ]);
        }

        $status = 'fail';
        session()->forget('code');
        session()->forget('zeroPaid');
        session()->forget('payment_status');
        session()->put('payment_status', 63);
        session()->put('code', $status);

        if($response->status == 'Pending'){

            $status = 'sucsess';
            session()->forget('payment_rep_id');
            session()->forget('payment_type');
            session()->forget('code');
            session()->forget('payment_status');
            session()->forget('total');
            // session()->forget('description');

            session()->put('payment_rep_id', $response->id);
            session()->put('payment_type', 'PayPal');
            session()->put('code', 'Pending');
            session()->put('payment_status', 63);
            session()->put('total', $total_after_vat);
            // session()->put('description', 'There is something went failed.');

            $url = $response->_links['redirect']['href'];
            return redirect()->away($url);

        }
        // return back();
        return response()->json([
            'url' => route('epay.payment.final_thanks', ['status'=>$status])
        ]);

    }

    // ================= End Payment Section ===============================

    function getCartMaster($user_id, $master_id = null){
        if($master_id) {
            // herrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
            $cartMasters = CartMaster::with('userId')
            ->where('id', $master_id)
            ->whereHas('carts', function(Builder $query){
                if(auth()->check()){
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'))->where('user_id', auth()->id());
                }else {
                    $query->whereNull('trashed_status')->whereNull('deleted_at')->where('payment_status', '!=', 68)->where('coin_id', session('coinID'));
                }
            })
            ->with(['carts.trainingOption', 'carts.session', 'carts.cartFeatures.trainingOptionFeature.feature', 'carts'=>function($query){
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
            ->with(['carts.trainingOption', 'carts.session', 'carts.cartFeatures.trainingOptionFeature.feature', 'carts'=>function($query){
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

        // dd($cartMasters);

        return $cartMasters;
    }

    function cartCheckout($user_id, $master_id = null) {

        $user = User::find($user_id);

        $cartMasters = $this->getCartMaster($user_id, $master_id);

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

            // if Free
            if($total_after_vat <= 0) {

                    session()->forget('zeroPaid');
                    session()->forget('code');
                    session()->put('zeroPaid', true);
                    session()->forget('payment_status');
                    session()->put('payment_status', 332);
                    $payment_status = 332;

                foreach ($cartMasters as $cartMaster) {
                    // $payment_status = 332;
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
            return view(FRONT.'.education.products.cart-checkout', compact('transaction_id', 'cartMasters', 'user', 'master_id', 'total_after_vat'));
        }
        return redirect()->route('education.hot-deals');
    }

    private function SendEmail($user, $cart){
        // $when = now()->addSeconds(5);
        Mail::to($user->email)->send(new Invoice($cart));
        Mail::to($user->email)
            ->cc(['accounting@bakkah.net.sa'])
            ->send(new PaymentReceipt($cart));//, 'mkassem@bakkah.net.sa'
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
        session()->forget('total');
        session()->put('code', 'paypal');
        session()->put('payment_status', 68);
        session()->put('total', $paid_in);

        $payment = PaymentTable::updateOrCreate([
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
            'total'=>$paid_in,
        ]);
    }

    public function cartPaypal(){

        // \Artisan::call('queue:work');
        // return;
        $user_id = request()->user_id;
        $paid_in = request()->paid_in;

        $total_after_vat = 0;

        $cartMasters = CartMaster::with('userId')
        ->where('total_after_vat', '!=', 0)
        ->where('user_id', $user_id)
        ->where('payment_status', '!=', 68)
        ->whereNull('trashed_status')
        ->where('coin_id', session('coinID'))
        ->get();

        foreach ($cartMasters as $cartMaster) {

            $total = GetCartTotalPriceAfterVat($cartMaster->id);
            $total_after_vat += $total;

            // Create Payment
            $payment = PaymentTable::updateOrCreate([
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
            // app('App\Http\Controllers\Front\Education\LMSController')->run($cartMaster->id, null);
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
        session()->forget('total');
        session()->put('code', 'paypal');
        session()->put('payment_status', 68);
        session()->put('total', $total_after_vat);

        // foreach($carts as $cart) {
        //     $payment = PaymentTable::updateOrCreate([
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
            'total'=>$total_after_vat,
        ]);
    }

    public function thanks(PaymentTable $payment, $status='success'){

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

            // if(session()->get('payment_type')=='PayPal' && session()->has('payment_rep_id')){

            if(session()->has('payment_rep_id')){
                // -------------------- PayPal -----------------------
                $secretKey = env('CHECHOUT_SECRET_KEY');
                $checkout = new CheckoutApi($secretKey, false);

                $payment_rep_id = session()->get('payment_rep_id');
                $details = $checkout->payments()->details($payment_rep_id);

                // dd($details);

                switch ($details->status){
                    case "Captured":  $status = 'sucsess'; break ;
                    case 'Authorized':  $status = 'fail'; break ;
                    case 'Pending':  $status = 'fail'; break ;
                    case "Declined":  $status = 'fail'; break ;
                    case "Verified":  $status = 'fail'; break ;
                    case "Card":  $status = 'fail'; break ;
                    default : $status = 'fail'; break ;
                }

                $total_after_vat = 0;

                session()->forget('code');
                session()->put('code', $status);
                session()->forget('description');
                session()->put('description', $details->status);

                if($status=='sucsess'){

                    $payment_status = 68;
                    $amount = $details->amount / 100;

                    session()->forget('total');
                    session()->put('total', $amount);
                    session()->forget('payment_status');
                    session()->put('payment_status', $payment_status);

                    $cartMasters = CartMaster::with('userId')
                    ->where('payment_rep_id', $payment_rep_id)
                    ->where('payment_status', 63)
                    ->where('type_id', 374)
                    ->whereNull('trashed_status')
                    ->where('coin_id', session('coinID'))
                    ->get();

                    foreach ($cartMasters as $cartMaster) {

                        $total_after_vat += GetCartTotalPriceAfterVat($cartMaster->id);
                        CartMaster::where('id', $cartMaster->id)->update([
                            'payment_status' => $payment_status,
                        ]);
                        // Create Payment
                        PaymentTable::updateOrCreate([
                                'master_id'=>$cartMaster->id,
                                'user_id' => $cartMaster->user_id??auth()->id(),
                            ],[
                                'paid_in'=>$total_after_vat,
                                'transaction_id'=>$cartMaster->transaction_id,
                                'payment_status'=>$payment_status,
                                // 'description'=>$description,
                                'code'=>$status,
                                'paid_at'=>DateTimeNow(),
                        ]);
                        // dd($cartMaster);
                        // zzzzzzzzzzzzzzzzzzzzzzzz
                        $this->UpdateCarts($cartMaster);

                        $this->SendEmailMaster($cartMaster->userId, $cartMaster->id);
                        app('App\Http\Controllers\Front\Education\LMSController')->run($cartMaster->id, null);
                    }
                }

            }
            return view(FRONT.'.education.products.register.thanks');
        }
        // return redirect()->route('education.index');
        return view(FRONT.'.education.products.register.thanks');
    }

}
