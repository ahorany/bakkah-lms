<?php
namespace App\Http\Controllers\Xero\Models;
use App\User;
use App\Models\Training\Cart;
use App\Models\Training\CartMaster;
use Exception;
use Illuminate\Database\Eloquent\Builder;

// ALTER TABLE `cart_masters` ADD `xero_prepayment` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL AFTER `deleted_at`, ADD `xero_prepayment_created_at` TIMESTAMP NULL DEFAULT NULL AFTER `xero_prepayment`;

// /public_html/app/Http/Controllers/Xero/Models

// **************************************
// If error sure if TAX002 is created from Xero -> (Accounting->Advanced->Tax Rates)
// **************************************
// Review and check if there is errors
// https://developer.xero.com/app/health/c4eff83a-4b17-4eb1-954c-9dbb2eaa4adb
// https://developer.xero.com/app/apphistory/c4eff83a-4b17-4eb1-954c-9dbb2eaa4adb?endpoint=&method=&status=&minDuration=&maxDuration=

// https://developer.xero.com/
// https://github.com/XeroAPI/xero-php-oauth2-app/blob/master/example.php
// https://github.com/XeroAPI/xero-php-oauth2
// https://xeroapi.github.io/xero-php-oauth2/docs/v2/accounting/index.html
// https://api-explorer.xero.com/accounting/banktransactions/getbanktransaction?path-banktransactionid=
// https://api-explorer.xero.com/accounting
// E:\Bakkah\Bakkah\New_System_Laravel\Education\LMS__Moodle_TalentLMS___Xero\Xero\Xero\vendor\vendor\xeroapi\xero-php-oauth2\lib\Api\AccountingApi.php


// Live BFT
// XERO_APP_NAME=BFT_BakkahForTraining
// XERO_CLIENT_ID="B0AAC4D2DB3047AF9E192A70F595F3F5"
// XERO_CLIENT_SECRET="vNOpS-jITM0ri6N060mtcQwxRnl7prgfXeU5Lkf74DY0yCKC"
// #XERO_CALLBACK=https://bakkah.com/xero/callback
// #XERO_WEBHOOK_KEY="qlNnq7qCXG65rsVeju0cIfUZx6Nk/LyHDYPpgCdz28IQTraWLNBMCA+ETv7lekqwwmrLM4F5V/Om/rOpgL/
// XTID="x30bftbakkahfortraining"

// Live
// XERO_APP_NAME=BakkahCom
// XERO_CLIENT_ID="C072F49D3DDF46D8BB0D16D3FF89E3EA"
// XERO_CLIENT_SECRET="Dwb797k8vHDvUVLehIDWS4KQgLj0L4hAJSCaXL_zsjABlzeW"
// #XERO_CALLBACK=https://bakkah.com/xero/callback
// #XERO_WEBHOOK_KEY="qlNnq7qCXG65rsVeju0cIfUZx6Nk/LyHDYPpgCdz28IQTraWLNBMCA+ETv7lekqwwmrLM4F5V/Om/rOpgL/eSA=="
// #XTID=x30bakkah

// Testing
// local test
// XERO_CLIENT_ID="B711564CFE7C41FEA534EC93C0F47024"
// XERO_CLIENT_SECRET="q0u4VNbCyXNu1ja64NDd0_TXFDqM8IF_pfVxxV0xFj2o0BAU"
// #XERO_WEBHOOK_KEY=+7xcpsUZLu91bnKoPuleXK4otCLlUczqGgiqVDkUA7biRxd/eeHLbR0a5jncGf0+9KY15DMi4gkxGBeCeDMo/A==
// #XERO_CALLBACK=https://localhost:8000/xero/auth/callback
// #XTID=x30localtest

class Prepayment {

    public $apiInstance = null;
    public $xeroTenantId = null;
    public $firstTrackingName = 'Region';
    public $secondTrackingName = 'Partnership';

    // Prepayment
    // public $prepayment_account_code = '620';
    public $bank_account_number_SAR = '0908007006543';
    public $bank_account_number_USD = '121314121314';

    public function __construct($apiInstance, $xeroTenantId){

        $this->apiInstance = $apiInstance;
        $this->xeroTenantId = $xeroTenantId;

        if(env('NODE_ENV')=='production'){
            $this->firstTrackingName = 'P&S_Category';
            $this->secondTrackingName = 'Partnership';

            // Prepayment
            // $this->prepayment_account_code = '11301';
            $this->bank_account_number_SAR = '13544'; // HYPERPAY
            $this->bank_account_number_USD = '11010502'; // PayPal-SAR
        }
    }

    public function create(){

        // dd($this->xeroTenantId);
        // dd($this->apiInstance);
        // dd(env('NODE_ENV'));
        // $this->createPrepayment($this->xeroTenantId,$this->apiInstance,$returnObj=false, null, null, null);
        // $this->getPrepayment($this->xeroTenantId,$this->apiInstance,$returnObj=false);

        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& Determine the Bank Account &&&&&&&&&&&&&&&&&&&&&&&&&&
            // https://go.xero.com/Bank/BankAccounts.aspx

            // $bank_account_number = $this->bank_account_number_SAR;  //SAR
            // if($cartMaster->coin_id==335){  // USD
            //     $bank_account_number = $this->bank_account_number_USD;  //USD
            // }
            // $getAccount = $this->getBankAccount($this->xeroTenantId,$this->apiInstance,$bank_account_number);

            // ---------------------------------------------------------------

            // $bank_account_number = '0908007006543';  //SAR
            // $coin_id = 'USD';
            // if($coin_id == 'USD'){
            //     $bank_account_number = '121314121314';  //USD
            // }
            // $getAccount = $this->getBankAccount($this->xeroTenantId,$this->apiInstance,$bank_account_number);
            // dd($getAccount);

        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& Determine the Account Expense &&&&&&&&&&&&&&&&&&&&&&&&&&
            // https://go.xero.com/GeneralLedger/ChartOfAccounts.aspx?accountClass=ACCTCLASS/ASSET&PageSize=200

            // ==================== IMPORTANT =========================
                // Need to set the code of the account here in live
                // $where = 'Code=="620"';  //Prepayments
            // ==================== IMPORTANT =========================

            // $account = $this->getAccountExpense($this->xeroTenantId,$this->apiInstance);
            // $accountCode = $account->getAccounts()[0]->getCode();
            // $coin_id = 'USD';
            // if($coin_id == 'USD'){
            //     $accountCode = $account->getAccounts()[1]->getCode();
            // }
            // dump($account);
            // dd($accountCode);

        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

        // &&&&&&&&&&&&&&&&&&&&& Get The Item Data &&&&&&&&&&&&&&&&&&&&&&&
            // dd('testtttt');

            // $item = $this->getItem($this->xeroTenantId,$this->apiInstance,'aPHRi-Course-OT');
            // $SalesAccountID = $item[0]['sales_details']['account_code']??'';

            // dd($SalesAccountID);
            // dd('Endeeee');
        // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

        // dd('Done_Yes');

        // if(env('NODE_ENV')=='production')
        // {
        //     $cartMasters = CartMaster::whereNull('xero_prepayment')
        //     ->whereNull('xero_prepayment_created_at')
        //     ->where('type_id', 374)
        //     ->whereNull('wp_migrate')
        //     ->whereHas('payment', function (Builder $query){
        //         $query->where('payment_status', 68);
        //     })
        //     ->with(['payment', 'carts'=>function($query){
        //         $query->whereNull('trashed_status')
        //               ->with(['trainingOption', 'course'=>function($query){
        //                 }, 'session'=>function($query){
        //                 }, 'cartFeatures.trainingOptionFeature.feature'=>function($query){
        //                 }]);
        //     }])
        //     // ->whereNotIn('id', [4300,4327])
        //     ->orderBy('id', 'asc')
        //     // ->where('id', 4178)  //13567
        //     // ->take(2)
        //     ->get();
        //     // dd($cartMasters);
        // }
        // else
        // {

            $cartMasters = CartMaster::whereNull('xero_prepayment')
            ->whereNull('xero_prepayment_created_at')
            ->where('type_id', 374)
            ->whereNull('wp_migrate')
            ->whereNull('trashed_status')
            ->whereHas('payment', function (Builder $query){
                $query->where('payment_status', 68);
            })
            ->whereHas('carts', function (Builder $query){
                $query->whereNull('trashed_status')
                      ->where('payment_status', 68);
            })
            ->with(['payment', 'carts'=>function($query){
                $query->whereNull('trashed_status')
                      ->with(['trainingOption'=>function($query){
                        }, 'course'=>function($query){
                        }, 'session'=>function($query){
                        }, 'cartFeatures.trainingOptionFeature.feature'=>function($query){
                        }]);
            }])
            // ->whereNotIn('id', [4300,4327])
            // ->whereNotIn('id', [4026])
            ->orderBy('id', 'asc')
            ->where('id', 14853)  //14877 SAR - 14854 USD
            // ->take(2)
            ->get();
            // dd($cartMasters);
        // } //if

        // dd($cartMasters);

        $i = 0;
        foreach($cartMasters as $cartMaster){

            $tax_type = 'TAX001'; //'TAX002'; old
            if(env('NODE_ENV')=='production'){
                $coin_id = ($cartMaster->coin_id==335) ? 'USD' : 'SAR';
                $tax_type = ($cartMaster->coin_id==335) ? 'OUTPUT' : 'TAX001'; //'TAX002'; //We need tax id from tax settings
            }else{
                $tax_type = 'TAX002';
                // $coin_id = null;
                $coin_id = ($cartMaster->coin_id==335) ? 'USD' : 'SAR';
                $tax_type = ($cartMaster->coin_id==335) ? 'NONE' : 'TAX002';
            }

            // Here add new rows in xero
            // wwwwwwwwwwwwwwwwwww

            // Asking amer Abu Ahmed
            // In Prepayment all items will have the same Account that will stored in $accountCode=prpayment as tamer says

            // $account = $this->getAccountExpense($this->xeroTenantId,$this->apiInstance);
            // $accountCode = $account->getAccounts()[0]->getCode();
            // dd($account);

            $contact = $this->Contact($cartMaster->user_id??null);

            $arr_lineItem = $this->ListItem($cartMaster, $tax_type);
            // $arr_lineItem = $this->ListItem($cartMaster, $tax_type, $accountCode);
            // dump($arr_lineItem);

            // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& Determine the Bank Account &&&&&&&&&&&&&&&&&&&&&&&&&&

                // $bank_account_number = '0908007006543';  //SAR
                // $bank_account_number = '121314121314';  //USD

                $bank_account_number = $this->bank_account_number_SAR;  //SAR
                if($cartMaster->coin_id==335){  // USD
                    $bank_account_number = $this->bank_account_number_USD;  //USD
                }
                $getAccount = $this->getBankAccount($this->xeroTenantId,$this->apiInstance,$bank_account_number);

                // dd($getAccount);
            // &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

            $paid_in = $cartMaster->payment->paid_in;
            if($cartMaster->coin_id==335){  // USD
                $paid_in = ($cartMaster->payment->paid_in) * USD_PRICE;
            }
            
            $ret_array = $this->createPrepayment($this->xeroTenantId,$this->apiInstance,$getAccount,false,$contact,$cartMaster, $arr_lineItem, [
                'cart_master_id'=>$cartMaster->id??'',
                'invoice_number'=>$cartMaster->invoice_number??'',
                'date'=>$cartMaster->payment->updated_at??now(),
                'paid'=>$paid_in,
                'coin_id'=>$coin_id,
            ]);

            $xero_prepayment = $ret_array['xero_prepayment']??null;
            $xero_prepayment_id = $ret_array['xero_prepayment_id']??null;

            CartMaster::where('id', $cartMaster->id)->update([
                'xero_prepayment'=>$xero_prepayment??null,
                'xero_prepayment_id'=>$xero_prepayment_id??null,
                'xero_prepayment_created_at'=>now(),
            ]);


            if($xero_prepayment && $xero_prepayment_id){
                echo ++$i.') Prepayment was created successfully for Master Invoice: <b>'.$cartMaster->invoice_number.'</b> with amount: <b>'.$paid_in.'</b><br><br>';
            }


            // CartMaster::where('id', $cartMaster->id)->update([
            //     'xero_prepayment'=>$prepayment??null,
            //     'xero_prepayment_created_at'=>now(),
            // ]);

            // $invoice_number = $this->Invoice($contact, $arr_lineItem, [
            //     'cart_master_id'=>$cartMaster->id,
            //     'invoice_number'=>$cartMaster->invoice_number,
            //     'date'=>$cartMaster->payment->updated_at,
            //     'paid'=>$cartMaster->payment->paid_in,
            //     'coin_id'=>$coin_id,
            // ]);

            // CartMaster::where('id', $cartMaster->id)->update([
            //     'xero_invoice'=>$invoice_number,
            //     'xero_invoice_created_at'=>now(),
            // ]);
        } //foreach

        dd('Done...Finish');

    } //public function create

    // public function Invoice($contact, $arr_lineItem, $array){

    //     $date = date_format($array['date'], 'Y-m-d');
    //     // $reference_date = date_format($array['date'], 'd-m-Y');

    //     $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
    //     $invoice->setContact($contact)
    //         ->setLineAmountTypes('Exclusive')
    //         ->setType("ACCREC")
    //         ->setCurrencyCode($array['coin_id'])
    //         ->setDate($date)
    //          // ->setDate($sessiondate)
    //         // ->setDate('2020-10-13')
    //         ->setDueDate($date)
    //         ->setReference('S.G.-'.$array['invoice_number'].'-HyperPay-MID.: '.$array['cart_master_id'])
    //         // ->setStatus('AUTHORISED')
    //         ->setAmountPaid($array['paid'])
    //         ->setLineItems($arr_lineItem);
    //     $arr_invoices = [];
    //     array_push($arr_invoices, $invoice);
    //     $invoices = new \XeroAPI\XeroPHP\Models\Accounting\Invoices;
    //     $invoices->setInvoices($arr_invoices);
    //     // dump($invoices);
    //     $apiResponse = $this->apiInstance->createInvoices($this->xeroTenantId,$invoices,true, 2);
    //     $invoiceR = $apiResponse->getInvoices();
    //     return $invoiceR[0]->getInvoiceNumber();
    // } //public function Invoice

    public function Contact($user_id){

        $user = User::find($user_id);
        $en_name = ($user->gender_id==43) ? 'Mr. '.$user->en_name : $user->en_name;
        // $en_name = 'Hani Salah';

        $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setName($en_name)
                ->setEmailAddress($user->email);
                // ->setContactId('Cust-'.$user_id);

            $arr_contacts = [];
            array_push($arr_contacts, $contact);
            $contacts = new \XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $contacts->setContacts($arr_contacts);
            $apiResponse = $this->apiInstance->updateOrCreateContacts($this->xeroTenantId, $contacts);
            // $message = 'New Contact Name: ' . $apiResponse->getContacts()[0]->getContactId();

        return $contact;
    } //public function Contact

    // public function getContact($xeroTenantId,$apiInstance,$returnObj=false)
	// {
	// 	$str = '';

	// 	$new = $this->createContacts($xeroTenantId,$apiInstance, true);
	// 	$contactId = $new->getContacts()[0]->getContactId();
    //     //[Contact:Read]
    //     $result = $apiInstance->getContacts($xeroTenantId, $contactId);
    //     //[/Contact:Read]

	// 	$str = $str . "Get specific Contact name: " . $result->getContacts()[0]->getName() . "<br>";

	// 	if($returnObj) {
	// 		return $result;
	// 	} else {
	// 		return $str;
	// 	}
	// }

    // public function getContacts($xeroTenantId,$apiInstance,$returnObj=false)
	// {
	// 	$str = '';

    //     //[Contacts:Read]
    //     // read all contacts
    //     $result = $apiInstance->getContacts($xeroTenantId);

    //     // filter by contacts by status
    //     $where = 'ContactStatus=="ACTIVE"';
    //     $result2 = $apiInstance->getContacts($xeroTenantId, null, $where);
    //     //[/Contacts:Read]

	// 	$str = $str . "Get Contacts Total: " . count($result->getContacts()) . "<br>";
	// 	$str = $str . "Get ACTIVE Contacts Total: " . count($result2->getContacts()) . "<br>";

	// 	if($returnObj) {
	// 		return $result2;
	// 	} else {
	// 		return $str;
	// 	}
	// }
// zzzzzzzzzzzzzzzzz
// $lineitem->setDescription('Something-' . $this->getRandNum())
//                     ->setQuantity(1)
//                     ->setUnitAmount(20)

    // public function ListItem($cartMaster, $tax_type, $accountCode){
    public function ListItem($cartMaster, $tax_type){
        $arr_lineItem = [];
        foreach($cartMaster->carts as $cart){

            // dump($cart->id.'====>'.$cart->course->xero_code.'==>'.$cart->course->xero_exam_code);
            $session_date = ' ('.date_format(date_create($cart->session->date_from), 'd-m-Y').')';

            $option_name = $cart->trainingOption->constant->en_name;
            if($cart->trainingOption->constant_id == 11 || $cart->trainingOption->constant_id == 353){ //self or exam simulation
                $session_date = '';
            }

            $itemCode = 'BOOK';
            // if(env('NODE_ENV')=='production'){
                $itemCode = $this->GenerateItemCode($cart);
            // }
            // $arr_lineItem = [];
            // if(isset($cart->course->xero_code) && !is_null($cart->course->xero_code))
            {
                // here get the $item Sales Account Code
                $item = $this->getItem($this->xeroTenantId,$this->apiInstance,$itemCode);
                $SalesAccountID = $item[0]['sales_details']['account_code']??'';

                $amount = $cart->price - $cart->discount_value;
                if($cartMaster->coin_id==335){  // USD
                    $amount = ($cart->price - $cart->discount_value) * USD_PRICE;
                }

                $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                    'itemCode'=>$itemCode,
                    'description'=>$cart->course->en_short_title.' - '.$option_name.''.$session_date,
                    'quantity'=>1,
                    'discountRate'=>0,
                    // 'unitAmount'=>$cart->price - $cart->discount_value,
                    'amount'=>$amount,
                    'accountCode'=>$SalesAccountID,
                    // 'accountCode'=>$accountCode,
                ]);
            }

            // if(env('NODE_ENV')=='production'){
                foreach($cart->cartFeatures as $cartFeature){

                    if($cartFeature->trainingOptionFeature->feature->id > 0 && !empty($cartFeature->trainingOptionFeature->xero_feature_code)){

                        $itemCode = $cartFeature->trainingOptionFeature->xero_feature_code??'-';
                        $item = $this->getItem($this->xeroTenantId,$this->apiInstance,$itemCode);
                        $SalesAccountID = $item[0]['sales_details']['account_code']??'';

                        $amount = $cartFeature->price;
                        if($cartMaster->coin_id==335){  // USD
                            $amount = ($cartFeature->price) * USD_PRICE;
                        }

                        $description = $itemCode;
                        if($cartFeature->trainingOptionFeature->feature->id == 5){
                            $description = json_decode($cartFeature->trainingOptionFeature->excerpt??'-')->en??'-';
                        }

                        $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                            'itemCode'=>$itemCode,
                            'description'=>$description,
                            'quantity'=>1,
                            'discountRate'=>0,
                            // 'unitAmount'=>$cartFeature->price,
                            'amount'=>$amount,
                            'accountCode'=>$SalesAccountID,
                        ]);

                    }

                    // // Exam Voucher
                    // if($cartFeature->trainingOptionFeature->feature->id == 1 && !empty($cart->course->xero_exam_code)){
                    //     $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                    //         'itemCode'=>$cart->course->xero_exam_code,
                    //         'description'=>'Exam Voucher',
                    //         'quantity'=>1,
                    //         'discountRate'=>0,
                    //         // 'unitAmount'=>$cartFeature->price,
                    //         'amount'=>$cartFeature->price,
                    //         'accountCode'=>$accountCode,
                    //     ]);
                    // }

                    // // Exam Simulation
                    // if($cartFeature->trainingOptionFeature->feature->id == 2 && !empty($cartFeature->cart->trainingOption->ExamSimulation->course->xero_code)){
                    //     $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                    //         'itemCode'=>$cartFeature->cart->trainingOption->ExamSimulation->course->xero_code,
                    //         'description'=>'Exam Simulation',
                    //         'quantity'=>1,
                    //         'discountRate'=>0,
                    //         // 'unitAmount'=>$cartFeature->price,
                    //         'amount'=>$cartFeature->price,
                    //         'accountCode'=>$accountCode,
                    //     ]);
                    // }

                    // // Take2
                    // if($cartFeature->trainingOptionFeature->feature->id == 3){
                    //     $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                    //         'itemCode'=>'Take2 Exam',
                    //         'description'=>'Take2 Exam',
                    //         // 'description'=>$cartFeature->trainingOptionFeature->feature->trans_title??'Take2',
                    //         'quantity'=>1,
                    //         'discountRate'=>0,
                    //         // 'unitAmount'=>$cartFeature->price,
                    //         'amount'=>$cartFeature->price,
                    //         'accountCode'=>$accountCode,
                    //     ]);
                    // }

                } //foreach

            // } //if(env('NODE_ENV')=='production'){

        } //foreach

        return $arr_lineItem;
    } //public function ListItem

    public function LineItem($arr_lineItem, $cart, $tax_type, $array=[]){

        $arr_lineItemTrackings = $this->LineItemTracking('Eastside', 'first test');
        if(env('NODE_ENV')=='production'){
            $arr_lineItemTrackings = $this->LineItemTracking($cart->course->postMorph->constant->xero_code??null
            , $cart->course->partner->xero_code??null);
        }

        $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;

        if(env('NODE_ENV')=='production'){
            // $lineItem->setItemCode($array['itemCode'])
            $lineItem->setDescription($array['description'])
            ->setQuantity($array['quantity'])
            // ->setAccountCode('200')
            ->setUnitAmount($array['amount']??0)
            // ->setDiscountRate($array['discountRate'])
            ->setTaxType($tax_type)//We need tax id from tax settings
            // ->setTaxType('TAX001')//TAX002 We need tax id from tax settings
            ->setTracking($arr_lineItemTrackings)
            ->setlineAmount($array['amount']??0)
            ->setAccountCode($array['accountCode']);
        }else{
            // $lineItem->setItemCode($array['itemCode'])
            $lineItem->setDescription($array['description'])
            ->setQuantity($array['quantity'])
            // ->setAccountCode('200')
            ->setUnitAmount($array['amount']??0)
            // ->setDiscountRate($array['discountRate'])
            ->setTaxType($tax_type)//We need tax id from tax settings
            ->setTracking($arr_lineItemTrackings)
            ->setlineAmount($array['amount']??0)
            ->setAccountCode($array['accountCode']);

        }

        array_push($arr_lineItem, $lineItem);

        return $arr_lineItem;
    } //public function LineItem

    protected function GenerateItemCode($cart){
        $array = [
            11=>'-SS',
            13=>'-OT',
            353=>'',
        ];
        return $cart->course->xero_code.$array[$cart->trainingOption->constant_id];
    } //protected function GenerateItemCode

    public function LineItemTracking($firstOption, $secondOption){

        $arr_lineItemTrackings = [];
        $lineItemTracking = new \XeroAPI\XeroPHP\Models\Accounting\LineItemTracking;
        $lineItemTracking->setName($this->firstTrackingName)
        ->setOption($firstOption);
        array_push($arr_lineItemTrackings, $lineItemTracking);

        $lineItemTracking = new \XeroAPI\XeroPHP\Models\Accounting\LineItemTracking;
        $lineItemTracking->setName($this->secondTrackingName)
        ->setOption($secondOption);
        array_push($arr_lineItemTrackings, $lineItemTracking);

        return $arr_lineItemTrackings;
    } //public function LineItemTracking


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++

            public function getPrepayment($xeroTenantId,$apiInstance,$returnObj=false)
            {
                $str = '';
                //[Prepayments:Read]
                // READ ALL

                $result = $apiInstance->getPrepayments($xeroTenantId);
                dd($result);
                //[/Prepayments:Read]
                $str = $str . "Get Prepayments: " . count($result->getPrepayments()) . "<br>";

                if($returnObj) {
                    return $result->getPrepayments()[0];
                } else {
                    return $str;
                }
            }

            public function createPrepayment($xeroTenantId,$apiInstance,$getAccount,$returnObj=false,$contact=null,$cartMaster=null, $arr_lineItem=null, $array)
            {
                // dd($arr_lineItem);
                $str = '';
                $date = date_format($array['date']??DateTimeNow(), 'Y-m-d');
                // if(env('NODE_ENV')!='production'){
                //     $lineitem = $this->getLineItemForPrepayment($xeroTenantId,$apiInstance);
                //     $arr_lineItem = [];
                //     array_push($arr_lineItem, $lineitem);
                // }

                // $getAccount = $this->getBankAccount($xeroTenantId,$apiInstance);
                // dd($getAccount);
                // here to determine the Bank getAccounts()[0] for the first and getAccounts()[1] for the second and so on....

                $accountId = $getAccount->getAccounts()[0]->getAccountId();
                $accountCode = $getAccount->getAccounts()[0]->getCode();

                // $contact = $this->Contact($cartMaster->user_id??2);
                // $contact = $this->Contact(2);

                // $contactId = $contact['contact_id']??null;
                // $contactName = $contact['name']??null;
                // $contactEmail = $contact['email_address']??null;
                // aaaaaaaaaaaaaaa

                // $getContact = $this->getContact($xeroTenantId,$apiInstance,true);
                // $contactId = $getContact->getContacts()[0]->getContactId();

                if (count($getAccount->getAccounts())) {

                    //[Prepayments:Create]
                    // $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
                    // // $contact->setContactId($contactId);
                    // $contact->setEmailAddress($contactEmail);

                    $bankAccount = new \XeroAPI\XeroPHP\Models\Accounting\Account;
                    $bankAccount->setCode($accountCode)
                        ->setAccountId($accountId);

                    $prepayment = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;

                    $prepayment->setContact($contact)
                                ->setLineAmountTypes("Exclusive")
                                ->setBankAccount($bankAccount)
                                ->setType(\XeroAPI\XeroPHP\Models\Accounting\BankTransaction::TYPE_RECEIVE_PREPAYMENT)
                                ->setDate($date)
                                ->setReference('S.G.-'.$array['invoice_number'].'-HyperPay-MID.: '.$array['cart_master_id'])
                                ->setLineItems($arr_lineItem);

                    // dd($prepayment);

                    // echo 'Start';
                    $result = $apiInstance->createBankTransactions($xeroTenantId,$prepayment,true, 2);
                    // echo 'Done';

                    $resultR = $apiInstance->getPrepayments($xeroTenantId, null, null, "Reference DESC");

                    $xero_prepayment = $resultR[0]['reference'];
                    $xero_prepayment_id = $resultR[0]->getPrepaymentId();

                    // vvvvvvvvvvvvvvvvvvvvvv

                    // dump($result);
                    // dump($resultR[0]->getPrepaymentId());
                    // dump($resultR[0]['reference']);
                    // return $resultR[0]['reference'];

                    return array('xero_prepayment' => $xero_prepayment, 'xero_prepayment_id'  => $xero_prepayment_id);

                    //[/Prepayments:Create]
                    // echo 'Success<br>';ssssssssssssssssssssssssssss
                }

                return null;

                // if(env('NODE_ENV')!='production'){
                //     $str = $str . "Created prepayment ID: " . $result->getBankTransactions()[0]->getPrepaymentId() . "<br>";
                // }

                // if($returnObj) {
                //     return $result;
                // } else {
                //     return $str;
                // }
            }

            // public function allocatePrepayment($xeroTenantId,$apiInstance)
            // {
            //     $str = '';

            //     $invNew = $this->createInvoiceAccRec($xeroTenantId,$apiInstance,true);
            //     $invoiceId = $invNew->getInvoices()[0]->getInvoiceID();
            //     $newPrepayement = $this->createPrepayment($xeroTenantId,$apiInstance,true);
            //     $prepaymentId = $newPrepayement->getBankTransactions()[0]->getPrepaymentId();

            //     //[Prepayments:Allocate]
            //     $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice ;
            //     $invoice->setInvoiceID($invoiceId);

            //     $prepayment = new \XeroAPI\XeroPHP\Models\Accounting\Prepayment ;
            //     $prepayment->setPrepaymentID($prepaymentId);

            //     $allocation = new \XeroAPI\XeroPHP\Models\Accounting\Allocation;
            //     $allocation->setInvoice($invoice)
            //         ->setAmount("1.00");
            //     $arr_allocation = [];
            //     array_push($arr_allocation, $allocation);

            //     $allocations = new \XeroAPI\XeroPHP\Models\Accounting\Allocations;
            //     $allocations->setAllocations($arr_allocation);

            //     $result = $apiInstance->createPrepaymentAllocation($xeroTenantId,$prepaymentId,$allocation);
            //     //[/Prepayments:Allocate]

            //     $str = $str . "Allocate Prepayment amount: " . $result->getAllocations()[0]->getAmount() . "<br>" ;

            //     return $str;
            // }

            // public function getLineItemForPrepayment($xeroTenantId,$apiInstance)
            // {
            //     // aaaaaaaaaaaaaaaaaaaa
            //     $account = $this->getAccountExpense($xeroTenantId,$apiInstance);

            //     // $lineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;

            //     $lineitem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
            //     $lineitem->setDescription('Something-' . $this->getRandNum())
            //         ->setQuantity(1)
            //         ->setUnitAmount(20)
            //         ->setAccountCode($account->getAccounts()[0]->getCode());
            //     return $lineitem;
            // }

            // public function getLineItemForPurchaseOrder($xeroTenantId,$apiInstance)
            // {
            //     $account = $this->getAccountRevenue($xeroTenantId,$apiInstance);

            //     $lineitem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
            //     $lineitem->setDescription('PO-' . $this->getRandNum())
            //         ->setQuantity(1)
            //         ->setUnitAmount(20)
            //         ->setAccountCode($account->getAccounts()[0]->getCode());
            //     return $lineitem;
            // }

            // public function getAccountExpense($xeroTenantId,$apiInstance)
            // {
            //     // dd(XeroAPI\XeroPHP\Models\Accounting\Account::STATUS_ACTIVE);

            //     // $where = 'Status=="' . \XeroAPI\XeroPHP\Models\Accounting\Account::STATUS_ACTIVE .'" AND Type=="' .  \XeroAPI\XeroPHP\Models\Accounting\Account::MODEL_CLASS_EXPENSE . '"';

            //     // if(env('NODE_ENV')=='production'){
            //     //     $where = 'Code=="620"';  //Prepayments
            //     // }else{
            //     //     $where = 'Code=="620"';  //Prepayments
            //     // }

            //     $where = 'Status=="ACTIVE" AND Code=="'. $this->prepayment_account_code .'"';  //Prepayments

            //     // $where = 'Status=="ACTIVE" AND Type=="EXPENSE"';
            //     // $where = 'Status=="ACTIVE" AND Type=="ASSET"';

            //     $result = $apiInstance->getAccounts($xeroTenantId, null, $where);

            //     return $result;
            // }

            public function getBankAccount($xeroTenantId,$apiInstance,$bank_account_number)
            {
                // READ only ACTIVE  ------ CurrencyCode
                $where = 'Status=="' . \XeroAPI\XeroPHP\Models\Accounting\Account::STATUS_ACTIVE .'" AND Type=="' .  \XeroAPI\XeroPHP\Models\Accounting\Account::BANK_ACCOUNT_TYPE_BANK . '" AND BankAccountNumber=="'. $bank_account_number .'"';

                $result = $apiInstance->getAccounts($xeroTenantId, null, $where);

                return $result;
            }

            public function getRandNum()
            {
                $randNum = strval(rand(1000,100000));

                return $randNum;
            }
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++
            public function getItem($xero_tenant_id, $apiInstance, $item_id, $unitdp = null)
            {
                list($response) = $apiInstance->getItemWithHttpInfo($xero_tenant_id, $item_id, $unitdp);
                return $response;
            }
            
}
