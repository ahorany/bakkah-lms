<?php
/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Import the class namespaces first, before using it directly
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Services\AdaptivePayments;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaymentController extends Controller
{

    protected $provider;

    public function __construct()
    {
        $this->provider = new ExpressCheckout();
    }

    public function index(){

        $provider = new PayPalClient;
        // dd($provider);
        $CartItems = [
            [
                'name'=>'Product 1',
                'price'=>9.99,
                'qty'=>1,
            ],
            [
                'name'=>'Product 2',
                'price'=>4.99,
                'qty'=>2,
            ],
        ];

        $checkoutData = [
            'items'=>$CartItems,

            'return_url'=>route('paypal.success'),
            'cancel_url'=>route('paypal.cancel'),
            'invoice_id'=>uniqid(),
            'invoice_description'=>'Order Description',
            'total'=> 14.98,
        ];
        // $provider = new ExpressCehckout();
        // $response = $provider->setExpressCheckout($checkoutData, true);
        // $response = $provider->setCurrency('USD')->setExpressCheckout($checkoutData);

        return view('front.education.paypal.code');
        // return view('front.education.paypal.index');
    }

    public function success(){
    }

    public function cancel(){
    }
}
*/
