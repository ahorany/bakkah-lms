<?php
namespace App\Http\Controllers\Xero\Models;
use App\User;
use App\Models\Training\Cart;
use App\Models\Training\CartMaster;
use Illuminate\Database\Eloquent\Builder;

// Review and check if there is errors
// https://developer.xero.com/app/apphistory/c4eff83a-4b17-4eb1-954c-9dbb2eaa4adb?endpoint=&method=&status=&minDuration=&maxDuration=
// https://developer.xero.com/
// https://github.com/XeroAPI/xero-php-oauth2-app/blob/master/example.php
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
// XERO_CLIENT_ID="B711564CFE7C41FEA534EC93C0F47024"
// XERO_CLIENT_SECRET="q0u4VNbCyXNu1ja64NDd0_TXFDqM8IF_pfVxxV0xFj2o0BAU"
// #XERO_WEBHOOK_KEY="YVuqgNwLuNnvCnuYpWfsgS3C0JhyVp1ECO4s+dPcKnjkME5GyJ7RiUmyIVWiDNdghe8mmX46NbA5qla2cQQDYw=="
// #XERO_WEBHOOK_KEY=+7xcpsUZLu91bnKoPuleXK4otCLlUczqGgiqVDkUA7biRxd/eeHLbR0a5jncGf0+9KY15DMi4gkxGBeCeDMo/A==
// #XERO_CALLBACK=https://localhost:8000/xero/auth/callback
// #XTID=x30localtest

class Invoice {

    public $apiInstance = null;
    public $xeroTenantId = null;
    public $firstTrackingName = 'Region';
    public $secondTrackingName = 'Partnership';
    public $session = null;
    public $training_option_id = null;

    public function __construct($apiInstance, $xeroTenantId, $session, $training_option_id){

        $this->apiInstance = $apiInstance;
        $this->xeroTenantId = $xeroTenantId;
        $this->session_id = $session??null;
        $this->training_option_id = $training_option_id??null;

        if(env('NODE_ENV')=='production'){
            $this->firstTrackingName = 'P&S_Category';
            $this->secondTrackingName = 'Partnership';
        }
    }

    public function create(){

        echo 'training_option_id = '.$this->training_option_id.' session_id = '.$this->session_id.'<br><br>';
        // dd('tttttttttttt');
        if($this->session_id==''){
            echo '<b>Invoices:</b><br>=======<br>';
        }else{
            echo '<b>Invoices for SID: '.$this->session_id.'</b><br>==================<br>';
        }

        // if($this->session_id){

            // dd(env('NODE_ENV'));

            // if(env('NODE_ENV')=='production')
            // {
            //     // whereNull('xero_invoice')
            //     // ->whereNull('xero_invoice_created_at')
            //     $cartMasters = CartMaster::where('type_id', 374)  // B2C
            //     ->whereHas('payment', function (Builder $query){
            //         $query->where('payment_status', 68);
            //     })
            //     ->with(['payment', 'carts'=>function($query){
            //         $query->whereNull('trashed_status')
            //             ->whereNull('xero_invoice')
            //             ->whereNull('xero_invoice_created_at')
            //             ->where('session_id', $this->session_id)
            //             ->where('payment_status', 68)  // Completed
            //             ->where('attend_type_id', 434) // Delivered

            //             ->with(['trainingOption',
            //                 'course'=>function($query_course){
            //             }, 'session'=>function($query_session){
            //             }, 'cartFeatures.trainingOptionFeature.feature'=>function($query_feature){
            //             }]);
            //     }])
            //     // ->whereIn('id', [4026])
            //     // ->whereNotIn('id', [4026])
            //     ->orderBy('id', 'asc')
            //     // ->where('id', 13567)
            //     // ->take(2)
            //     ->get();
            //     // dd($cartMasters);
            // }
            // else
            // {
                // whereNull('xero_invoice')
                // ->whereNull('xero_invoice_created_at')
                $cartMasters = CartMaster::where('type_id', 374)  // B2C
                ->whereNull('trashed_status')
                ->whereHas('payment', function (Builder $query){
                    $query->where('payment_status', 68);
                })
                ->whereHas('carts', function (Builder $query){
                    $query->whereNull('trashed_status')
                        ->whereNull('xero_invoice')
                        ->whereNull('xero_invoice_created_at')
                        ->where('payment_status', 68);  // Completed

                            if($this->session_id){
                                $query->where('session_id', $this->session_id);
                            }

                            //Online or ClassRoom
                            if($this->training_option_id == 13 || $this->training_option_id == 383){
                                $query->where('attend_type_id', 434); // Delivered
                            }
                })
                ->whereHas('carts.trainingOption', function (Builder $query){
                    //Self-Study or Exam Simulation
                    if($this->training_option_id == 11 || $this->training_option_id == 353){
                        $query->where('constant_id', $this->training_option_id??null);
                    }
                })

                ->with(['payment', 'carts'=>function($query){
                        $query->with(['trainingOption'=>function($query){
                        }, 'course'=>function($query_course){
                        }, 'session'=>function($query_session){
                        }, 'cartFeatures.trainingOptionFeature.feature'=>function($query_feature){
                        }]);
                }])
                // ->whereIn('id', [4026])
                // ->whereNotIn('id', [4026])
                ->orderBy('id', 'asc')
                ->where('id', 10711) //1142153983
                // ->take(2)
                ->get();
                // dd($cartMasters->toSql());
                // dd($cartMasters);
            // } //if

            // dd($cartMasters->toSql());
            dd($cartMasters);

            // foreach($cartMasters as $cartMaster){
            //     echo 'z';
            //     dump($cartMaster);
            // }
            // dd('xxxxxxxxxxxxxxxxxxx');

            // dump($this->session_id??null);

            $i = 0;
            foreach($cartMasters as $cartMaster){

                // dump($cartMaster->carts->sum('total_after_vat'));
                // dd($cartMaster->carts->sum('refund_value_after_vat'));

                $tax_type = 'TAX001'; //'TAX002'; old
                if(env('NODE_ENV')=='production'){
                    $coin_id = ($cartMaster->coin_id==335) ? 'USD' : 'SAR';
                    $tax_type = ($cartMaster->coin_id==335) ? 'OUTPUT' : 'TAX001'; //'TAX002'; //We need tax id from tax settings
                }else{
                    $tax_type = 'TAX002';
                    $coin_id = null;
                    // $coin_id = ($cartMaster->coin_id==335) ? 'USD' : 'SAR';
                    $tax_type = ($cartMaster->coin_id==335) ? 'NONE' : 'TAX002';
                }

                // Here add new rows in xero

                // $account = $this->getAccountExpense($this->xeroTenantId,$this->apiInstance);
                // $accountCode = $account->getAccounts()[0]->getCode();
                // dd($accountCode);

                $contact = $this->Contact($cartMaster->user_id??null);

                // $arr_lineItem = $this->ListItem($cartMaster, $tax_type, $accountCode);

                $cart = $cartMaster->carts[0]??null;

                $arr_lineItem = $this->ListItem($cartMaster, $tax_type, $cart);
                // dd($arr_lineItem);

                $ret_array = $this->Invoice($cart, $contact, $arr_lineItem, [
                    'cart_master_id'=>$cartMaster->id,
                    'invoice_number'=>$cartMaster->invoice_number,
                    'date'=>$cartMaster->payment->updated_at,
                    'paid'=>($cart->total_after_vat??0) - ($cart->refund_value_after_vat??0),
                    // 'paid'=>($cartMaster->carts->sum('total_after_vat')??0) - ($cartMaster->carts->sum('refund_value_after_vat')??0),
                    // 'paid'=>$cartMaster->payment->paid_in,
                    'coin_id'=>$coin_id,
                    'xero_prepayment_id'=>$cartMaster->xero_prepayment_id??null,
                ]);

                $invoice_number = $ret_array['invoice_number']??null;
                $xero_invoice_id = $ret_array['xero_invoice_id']??null;
                $allocate_prepayment_amount = $ret_array['allocate_prepayment_amount']??null;

                if($xero_invoice_id && $invoice_number){
                    echo ++$i.') Invoice was created successfully for Master Invoice: <b>'.$cartMaster->invoice_number.'</b>';
                    if($allocate_prepayment_amount){
                        echo '</b> and allocate <b>'.$allocate_prepayment_amount.'</b> prepayment with it.';
                    }
                    echo '<br><br>';
                }

                // Cart::where('master_id', $cartMaster->id)->update([
                //     'xero_invoice'=>$invoice_number,
                //     'xero_invoice_id'=>$xero_invoice_id,
                //     'xero_invoice_created_at'=>now(),
                // ]);
            } //foreach

            dd('Done');

        // }

    } //public function create

    public function Invoice($cart, $contact, $arr_lineItem, $array){

        // $date = date_format($array['date'], 'Y-m-d');
        // $reference_date = date_format($array['date'], 'd-m-Y');

        $date = date_format(date_create($cart->session->date_to), 'Y-m-d');

        //self or exam simulation
        if($cart->trainingOption->constant_id == 11 || $cart->trainingOption->constant_id == 353){
            // if so the date will be $array['date'] = $cartMaster->payment->updated_at
            $date = date_format($array['date'], 'Y-m-d');
        }

        $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
        $invoice->setContact($contact)
            ->setLineAmountTypes('Exclusive')
            ->setType("ACCREC")
            ->setCurrencyCode($array['coin_id'])
            ->setDate($date)
             // ->setDate($sessiondate)
            // ->setDate('2020-10-13')
            ->setDueDate($date)
            ->setReference('S.G.-'.$array['invoice_number'].'-HyperPay-MID.: '.$array['cart_master_id'])
            ->setStatus('AUTHORISED')
            ->setAmountPaid($array['paid'])
            ->setLineItems($arr_lineItem);
        $arr_invoices = [];
        array_push($arr_invoices, $invoice);
        $invoices = new \XeroAPI\XeroPHP\Models\Accounting\Invoices;
        $invoices->setInvoices($arr_invoices);
        // dump($invoices);
        $apiResponse = $this->apiInstance->createInvoices($this->xeroTenantId,$invoices,true, 2);
        $invoiceR = $apiResponse->getInvoices();

        $invoice_number = $invoiceR[0]->getInvoiceNumber();
        $xero_invoice_id = $invoiceR[0]->getInvoiceID();

        // Cart::where('master_id', $array['cart_master_id'])->update([
        $cart->update([
            'xero_invoice'=>$invoice_number,
            'xero_invoice_id'=>$xero_invoice_id,
            'xero_invoice_created_at'=>now(),
        ]);

        // herrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
        // call her the paymentAllocation

        $allocate_prepayment_amount = $this->allocatePrepayment($this->xeroTenantId,$this->apiInstance, $invoiceR[0], $array['xero_prepayment_id'], $array['paid']);

        return array('invoice_number' => $invoice_number, 'xero_invoice_id'  => $xero_invoice_id, 'allocate_prepayment_amount' => $allocate_prepayment_amount);

        // return $invoiceR[0]->getInvoiceNumber();
    } //public function Invoice

    public function Contact($user_id){

        $user = User::find($user_id);
        $en_name = ($user->gender_id==43) ? 'Mr. '.$user->en_name : $user->en_name;

        $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
            $contact->setName($en_name)
                ->setEmailAddress($user->email);

            $arr_contacts = [];
            array_push($arr_contacts, $contact);
            $contacts = new \XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $contacts->setContacts($arr_contacts);
            $apiResponse = $this->apiInstance->updateOrCreateContacts($this->xeroTenantId, $contacts);
            // $message = 'New Contact Name: ' . $apiResponse->getContacts()[0]->getContactId();

        return $contact;
    } //public function Contact

    public function ListItem($cartMaster, $tax_type, $cart){
        $arr_lineItem = [];

        if($cart){
        // foreach($cartMaster->carts as $cart){

            // dd($cart);

            // dump($cart->id.'====>'.$cart->course->xero_code.'==>'.$cart->course->xero_exam_code);
            $session_date = ' ('.date_format(date_create($cart->session->date_from), 'd-m-Y').')';

            $option_name = $cart->trainingOption->constant->en_name;
            if($cart->trainingOption->constant_id == 11 || $cart->trainingOption->constant_id == 353){ //self or exam simulation
                $session_date = '';
            }

            $itemCode = 'BOOK';
            if(env('NODE_ENV')=='production'){
                $itemCode = $this->GenerateItemCode($cart);
            }
            // $arr_lineItem = [];
            // if(isset($cart->course->xero_code) && !is_null($cart->course->xero_code))
            {
                $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                    'itemCode'=>$itemCode,
                    'description'=>$cart->course->en_short_title.' - '.$option_name.''.$session_date,
                    'quantity'=>1,
                    // 'discountRate'=>$cart->discount,
                    'discountRate'=>0,
                    // 'amount'=>1590.01,
                    'amount'=>$cart->price - $cart->discount_value,
                ]);
            }

            if(env('NODE_ENV')=='production'){
                foreach($cart->cartFeatures as $cartFeature){
                    // Exam Voucher
                    if($cartFeature->trainingOptionFeature->feature->id == 1 && !empty($cart->course->xero_exam_code)){
                        $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                            // 'itemCode'=>$itemCode,
                            'itemCode'=>$cart->course->xero_exam_code,
                            'description'=>'Exam Voucher',
                            // 'description'=>$cartFeature->trainingOptionFeature->feature->trans_title??'Exam Voucher',
                            'quantity'=>1,
                            'discountRate'=>0,
                            'amount'=>$cartFeature->price,
                        ]);
                    }

                    // Exam Simulation
                    if($cartFeature->trainingOptionFeature->feature->id == 2 && !empty($cartFeature->cart->trainingOption->ExamSimulation->course->xero_code)){
                        $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                            // 'itemCode'=>$itemCode,
                            'itemCode'=>$cartFeature->cart->trainingOption->ExamSimulation->course->xero_code,
                            'description'=>'Exam Simulation',
                            // 'description'=>$cartFeature->trainingOptionFeature->feature->trans_title??'Exam Simulation',
                            'quantity'=>1,
                            'discountRate'=>0,
                            'amount'=>$cartFeature->price,
                        ]);
                    }

                    // Take2
                    if($cartFeature->trainingOptionFeature->feature->id == 3){
                        $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                            // 'itemCode'=>$itemCode,
                            'itemCode'=>'Take2 Exam',
                            'description'=>'Take2 Exam',
                            // 'description'=>$cartFeature->trainingOptionFeature->feature->trans_title??'Take2',
                            'quantity'=>1,
                            'discountRate'=>0,
                            'amount'=>$cartFeature->price,
                        ]);
                    }

                    // Exam Voucher
                    if($cartFeature->trainingOptionFeature->feature->id == 4 && !empty($cart->course->xero_exam_code_practitioner)){
                        $arr_lineItem = $this->LineItem($arr_lineItem, $cart, $tax_type, [
                            'itemCode'=>$cart->course->xero_exam_code_practitioner,
                            'description'=>'Practitioner Exam Voucher',
                            // 'description'=>$cartFeature->trainingOptionFeature->feature->trans_title??'Take2',
                            'quantity'=>1,
                            'discountRate'=>0,
                            'amount'=>$cartFeature->price,
                        ]);
                    }
                } //foreach
            } //if(env('NODE_ENV')=='production'){
        // } //foreach
        }

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
            $lineItem->setItemCode($array['itemCode'])
            ->setDescription($array['description'])
            ->setQuantity($array['quantity'])
            // ->setAccountCode('200')
            ->setUnitAmount($array['amount'])
            ->setDiscountRate($array['discountRate'])
            ->setTaxType($tax_type)//We need tax id from tax settings
            // ->setTaxType('TAX001')//TAX002 We need tax id from tax settings
            ->setTracking($arr_lineItemTrackings);
        }else{
            $lineItem->setItemCode($array['itemCode'])
            ->setDescription($array['description'])
            ->setQuantity($array['quantity'])
            // ->setAccountCode('200')
            ->setUnitAmount($array['amount'])
            ->setDiscountRate($array['discountRate'])
            ->setTaxType($tax_type)//We need tax id from tax settings
            ->setTracking($arr_lineItemTrackings);
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

        //     public function getPrepayment($xeroTenantId,$apiInstance,$returnObj=false)
        //     {
        //         $str = '';
        //         //[Prepayments:Read]
        //         // READ ALL
        //         $result = $apiInstance->getPrepayments($xeroTenantId);
        //         //[/Prepayments:Read]
        //         $str = $str . "Get Prepayments: " . count($result->getPrepayments()) . "<br>";

        //         if($returnObj) {
        //             return $result->getPrepayments()[0];
        //         } else {
        //             return $str;
        //         }
        //     }


        //     public function createPrepayment($xeroTenantId,$apiInstance,$returnObj=false)
        //     {
        //         $str = '';

        //         $lineitem = $this->getLineItemForPrepayment($xeroTenantId,$apiInstance);
        //         $lineitems = [];
        //         array_push($lineitems, $lineitem);

        //         $getAccount = $this->getBankAccount($xeroTenantId,$apiInstance);
        //         $accountId = $getAccount->getAccounts()[0]->getAccountId();
        //         $accountCode = $getAccount->getAccounts()[0]->getCode();

        //         $getContact = $this->getContact($xeroTenantId,$apiInstance,true);
        //         $contactId = $getContact->getContacts()[0]->getContactId();

        //         if (count($getAccount->getAccounts())) {

        // //[Prepayments:Create]
        // $contact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
        // $contact->setContactId($contactId);

        // $bankAccount = new XeroAPI\XeroPHP\Models\Accounting\Account;
        // $bankAccount->setCode($accountCode)
        //     ->setAccountId($accountId);

        // $prepayment = new XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
        // $prepayment->setReference('Ref-' . $this->getRandNum())
        //     ->setDate(new DateTime('2017-01-02'))
        //     ->setType(XeroAPI\XeroPHP\Models\Accounting\BankTransaction::TYPE_RECEIVE_PREPAYMENT)
        //     ->setLineItems($lineitems)
        //     ->setContact($contact)
        //     ->setLineAmountTypes("NoTax")
        //     ->setBankAccount($bankAccount)
        //     ->setReference("Sid Prepayment 2");

        // $result = $apiInstance->createBankTransactions($xeroTenantId,$prepayment);
        // //[/Prepayments:Create]
        //         }

        //         $str = $str . "Created prepayment ID: " . $result->getBankTransactions()[0]->getPrepaymentId() . "<br>";

        //         if($returnObj) {
        //             return $result;
        //         } else {
        //             return $str;
        //         }
        //     }

            public function allocatePrepayment($xeroTenantId,$apiInstance,$invoice,$prepaymentId,$ivoiceAmount)
            {
                // $str = '';

                // $invNew = $this->createInvoiceAccRec($xeroTenantId,$apiInstance,true);
                // $invoiceId = $invNew->getInvoices()[0]->getInvoiceID();
                // $newPrepayement = $this->createPrepayment($xeroTenantId,$apiInstance,true);
                // $prepaymentId = $newPrepayement->getBankTransactions()[0]->getPrepaymentId();

                // //[Prepayments:Allocate]
                // $invoice = new XeroAPI\XeroPHP\Models\Accounting\Invoice ;
                // $invoice->setInvoiceID($invoiceId);

                // $prepayment = new XeroAPI\XeroPHP\Models\Accounting\Prepayment ;
                // $prepayment->setPrepaymentID($prepaymentId);

                // dump($apiInstance);
                // echo '<br>';
                // dump($invoice);
                // echo '<br>';
                // dump($prepaymentId);
                // echo '<br>';
                // dump($ivoiceAmount);
                // echo '<br>';

//                 $prepaymentID = 'prepaymentID_example'; // string |
//                 $allocations = { "Allocations":[ { "Invoice":{ "LineItems":[
// ], "InvoiceID":"c7c37b83-ac95-45ea-88ba-8ad83a5f22fe" }, "Amount":1.0, "Date":"2019-03-13" } ] }; // \Consilience\Xero\AccountingSdk\Model\Allocations |

                $allocation = new \XeroAPI\XeroPHP\Models\Accounting\Allocation;
                $allocation->setInvoice($invoice)
                    ->setAmount($ivoiceAmount);
                    // ->setAmount("1.00");
                $arr_allocation = [];
                array_push($arr_allocation, $allocation);

                $allocations = new \XeroAPI\XeroPHP\Models\Accounting\Allocations;
                $allocations->setAllocations($arr_allocation);

                // dump($allocations);

                $result = $apiInstance->createPrepaymentAllocations($xeroTenantId,$prepaymentId,$allocation);
                //[/Prepayments:Allocate]

                // echo '<br>Allocate<br>';
                // dump($result);
                return $result->getAllocations()[0]->getAmount();

                // return false;

                // $str = $str . "Allocate Prepayment amount: " . $result->getAllocations()[0]->getAmount() . "<br>" ;

                // return $str;
            }
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++
}
