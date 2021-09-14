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
                <div class="col-md-8">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Frames v2</title>
    <style>*,*::after,*::before{box-sizing:border-box}html{padding:1rem;background-color:#FFF;font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif}#payment-form{width:31.5rem;margin:0 auto}iframe{width:100%}.one-liner{display:flex;flex-direction:column}#pay-button{border:none;border-radius:3px;color:#FFF;font-weight:500;height:40px;width:100%;background-color:#13395E;box-shadow:0 1px 3px 0 rgba(19,57,94,0.4)}#pay-button:active{background-color:#0B2A49;box-shadow:0 1px 3px 0 rgba(19,57,94,0.4)}#pay-button:hover{background-color:#15406B;box-shadow:0 2px 5px 0 rgba(19,57,94,0.4)}#pay-button:disabled{background-color:#697887;box-shadow:none}#pay-button:not(:disabled){cursor:pointer}.card-frame{border:solid 1px #13395E;border-radius:3px;width:100%;margin-bottom:8px;height:40px;box-shadow:0 1px 3px 0 rgba(19,57,94,0.2)}.card-frame.frame--rendered{opacity:1}.card-frame.frame--rendered.frame--focus{border:solid 1px #13395E;box-shadow:0 2px 5px 0 rgba(19,57,94,0.15)}.card-frame.frame--rendered.frame--invalid{border:solid 1px #D96830;box-shadow:0 2px 5px 0 rgba(217,104,48,0.15)}.success-payment-message{color:#13395E;line-height:1.4}.token{color:#b35e14;font-size:0.9rem;font-family:monospace}@media screen and (min-width: 31rem){.one-liner{flex-direction:row}.card-frame{width:318px;margin-bottom:0}#pay-button{width:175px;margin-left:8px}}</style>
</head>

<body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<!-- add frames script -->
<script src="https://cdn.checkout.com/js/framesv2.min.js"></script>

<form id="payment-form" method="POST" action="https://merchant.com/charge-card">
    <div class="one-liner">
        <div class="card-frame">
            <!-- form will be added here -->
        </div>
        <!-- add submit button -->
        <button id="pay-button" disabled>
            PAY GBP 24.99
        </button>
    </div>
    <p class="success-payment-message"></p>
</form>

<script>
    var payButton = document.getElementById("pay-button");
    var form = document.getElementById("payment-form");

    Frames.init("pk_test_5977eaa1-1404-41e7-85bf-185fcbcee2bd");

    Frames.addEventHandler(
        Frames.Events.CARD_VALIDATION_CHANGED,
        function (event) {
            console.log("CARD_VALIDATION_CHANGED: %o", event);

            payButton.disabled = !Frames.isCardValid();
        }
    );

    Frames.addEventHandler(
        Frames.Events.CARD_TOKENIZED,
        function (event) {
            var el = document.querySelector(".success-payment-message");
            el.innerHTML = "Card tokenization completed<br>" +
                "Your card token is: <span class=\"token\">" + event.token + "</span>";

            //////////

            let t = event.token;

            $.ajax(
                {
                    url: "pay/"+t,
                    type: 'POST',
                    data: {
                        "token": t,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function (data)
                    {
                      console.log(data);
                    },error: function (rej)
                    {

                    }
                });


        }
    );

    form.addEventListener("submit", function (event) {
        payButton.disabled = true // disables pay button once submitted
        event.preventDefault();
        Frames.submitCard();
    });
</script>

</body>

</html>
