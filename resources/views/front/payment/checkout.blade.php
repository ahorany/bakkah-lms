@extends(FRONT.'.education.layouts.master')

@section('useHead')
    <title>{{__('education.Checkout')}}</title>

    <style>
        .wpwl-form-card{ min-height: 0px !important; }
        .wpwl-label-brand{ /* display: none !important; */ }
        .wpwl-control-brand{ /* display: none !important; */ }
        .wpwl-brand-card
        {
            display: block;
            visibility: visible;
            position: absolute;
            right: 160px;
            top: 65px;
            width: 67px;
            z-index: 10;
        }
        .wpwl-brand-MASTER{ margin-top: -10; margin-right: -10; }
    </style>
    <!-- add this line of code if you didn't have Jquery in your website -->
    <script src="https://code.jquery.com/jquery.js" type="text/javascript"></script>

    <script>
        var lang = $('html').attr('lang');
        // console.log(lang);
        var wpwlOptions = {
            paymentTarget: '_top',
            numberFormatting: false,
            locale: lang, //change it based on the werbsite language

            onReady: function(){
                // $(".wpwl-brand").css("display","none");
            if (wpwlOptions.locale == "ar") {
                $(".wpwl-group").css('direction', 'ltr');
                $(".wpwl-control-cardNumber").css({'direction': 'ltr' , "text-align":"right"});
                $(".wpwl-brand-card").css('right', '150px');
                $(".wpwl-control").css('text-align', 'right');
            }

            },onDetectBrand: function(brands){
                $(".wpwl-brand").css("display","block");
            }
        };
    </script>

@endsection

@section('content')

<?php
$type = $cart->trainingOption->constant->slug;
$subtitle = __('education.Welcome,') . ' <strong>' . $cart->userId->trans_name . '</strong> '.  __('education.You are now paying for the') . ' (<strong>'.$cart->course->trans_title .'</strong>) '. __('education.that will be held on') . ' (<strong>'. $cart->session->published_from. ' - ' . $cart->session->published_to . '</strong>)';
if($type == 'custom-date') {
    $subtitle = __('education.Welcome,') . ' <strong>' . $cart->userId->trans_name . '</strong> '.  __('education.You are now paying for the') . ' (<strong>'.$cart->course->trans_title .'</strong>)';
}
?>

    @include(FRONT.'.education.Html.page-header',
    [
        'title'=>__('education.Checkout') . ' | ' . $cart->course->trans_title,
        'subtitle' => $subtitle
    ])

    <div class="main-content py-5">

        @if(env('NODE_ENV')!='production')
            <h2 style="background: black;color: yellow;text-align: center;margin-bottom: 40px;padding: 15px;">Test Paymengt Gateway</h2>
        @endif

        <div class="container container-padding">
            <div class="row mt-3">
                <div class="col-md-8">
                    <?php
                    $countryID = GetCountryID();
                    $coinID = $cart->coin_id;
                    $currency_code = 'SAR';
                    if($coinID != 334)
                        $currency_code = 'USD';
                    ?>

                    @if ($countryID == 58)
                        <script src="{{env('payment_url')}}/paymentWidgets.js?checkoutId={{$transaction_id}}"></script>
                        <form action="{{route('epay.payment.post')}}" class="paymentWidgets" data-brands="MADA VISA MASTER AMEX"></form>
                    @else

                        @if (App::environment('production'))
                            <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_LIVE_CLIENT_ID')}}&currency=USD&disable-funding=credit,card"></script>
                        @else
                            <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_CLIENT_ID')}}&currency=USD&disable-funding=credit,card"></script>
                        @endif
                        <div style="width: 60%;margin: 0 auto;" id="paypal-button-container"></div>

                        <script>
                            paypal.Buttons({
                            style: {
                                color:  'blue',
                                label:  'pay',
                                height: 50
                            },
                            createOrder: function(data, actions) {
                                // This function sets up the details of the transaction, including the amount and line item details.
                                return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '{{$cart->total_after_vat}}',
                                        currency_code:'USD',
                                    }
                                }]
                                });
                            },
                            onApprove: function(data, actions) {
                                // This function captures the funds from the transaction.
                                return actions.order.capture().then(function(details) {
                                // This function shows a transaction success message to your buyer.
                                    // alert('Transaction completed by ' + details.payer.name.given_name);
                                    window.location.href = "{{route('epay.payment.paypal')}}?cart_id={{$cart->id}}&paid_in={{$cart->total_after_vat}}";
                                    {{--$.ajax({
                                        type:'get',
                                        url:'{{route("epay.payment.paypal")}}',
                                        data:{
                                            cart_id:'{{$cart->id}}',
                                            paid_in:'{{$cart->total_after_vat}}',
                                        },
                                        success:function(data){
                                            // Simulate an HTTP redirect:
                                            window.location.replace("{{route('epay.payment.final_thanks', ['status'=>'success'])}}");
                                        }
                                    });--}}
                                });
                            }
                            }).render('#paypal-button-container');
                            //This function displays Smart Payment Buttons on your web page.
                        </script>

                    @endif

                </div>

                <div class="col-md-4">
                    @include(FRONT.'.education.products.components.summary.order-summary-payment', ['cart'=>$cart])
                </div>
            </div>
        </div>
    </div>
@endsection
