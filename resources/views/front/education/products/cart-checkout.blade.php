@extends(FRONT.'.education.layouts.master')
@section('style')
    <link rel="stylesheet" href="{{CustomAsset('front-dist/css/checkout-style.css')}}">
    @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
        <link rel="stylesheet" href="{{CustomAsset('front-dist/css/checkout-style-rtl.css')}}">
    @endif
@endsection
@section('useHead')
    <title>{{__('education.Checkout')}}</title>

    <style>

        html{
            padding: 0 !important;
        }

        .wpwl-form-card{ min-height: 0px !important; }
        .wpwl-label-brand, .wpwl-wrapper-brand{ display: none !important; }
        .wpwl-label-brand{ /* display: none !important; */ }
        .wpwl-control-brand{ /* display: none !important; */ }
        .wpwl-brand-card
        {
            display: block;
            visibility: visible;
            position: absolute;
            right: 160px;
            top: 36px;
            width: 67px;
            z-index: 10;
        }
        .wpwl-brand-MASTER{ margin-top: -10; margin-right: -10; }
        #paypal2-button-container:hover {
            background-color: #fb4400 !important;
            box-shadow: 0 2px 5px 0 rgb(19 57 94 / 40%);
        }

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
                $(".wpwl-control-cardHolder").attr('placeholder', 'اسمك الظاهر على البطاقة بالإنجليزي');
                $(".wpwl-control-cvv").attr('placeholder', 'الثلاث أرقام خلف البطاقة');
            }

            },onDetectBrand: function(brands){
                $(".wpwl-brand").css("display","block");
            }
        };
    </script>
@endsection

@section('content')

<?php
// $type = $cart->trainingOption->constant->slug;
// $subtitle = __('education.Welcome,') . ' <strong>' . $cart->userId->trans_name . '</strong> '.  __('education.You are now paying for the') . ' (<strong>'.$cart->course->trans_title .'</strong>) '. __('education.that will be held on') . ' (<strong>'. $cart->session->published_from. ' - ' . $cart->session->published_to . '</strong>)';
// if($type == 'custom-date') {
    //$subtitle = __('education.Welcome,') . ' <strong>' . $cart->userId->trans_name . '</strong> '.  __('education.You are now paying for the') . ' (<strong>'.$cart->course->trans_title .'</strong>)';
//}
$items = '';
$subtitle = __('education.Welcome,') . ' <strong>' . $user->trans_name . '</strong> '.  __('education.You are now paying for the'). ' (';
?>
@foreach ($cartMasters as $cartMaster)
    @foreach ($cartMaster->carts as $cart)
    <?php
    $subtitle .= $cart->course->trans_title??'';
    $subtitle .= ($loop->last) ? '' : ', ';
    $items .= $cart->course->trans_short_title??'';
    $items .= ($loop->last) ? '' : ', ';
    ?>
    @endforeach
    <?php
    $subtitle .= ($loop->last) ? '' : ', ';
    $items .= ($loop->last) ? '' : ', ';
    $items .= ' | InvNo: '.$cartMaster->invoice_number??'';
    ?>
@endforeach
<?php
$subtitle = '<strong>'.$subtitle.'</strong>)';
?>

    @include(FRONT.'.education.Html.page-header',
    [
        'title'=>__('education.Checkout'),
        'subtitle' => $subtitle
    ])
</div>
    <div class="main-content py-5">

        @if(env('NODE_ENV')!='production')
            <h2 style="background: black;color: yellow;text-align: center;margin-bottom: 40px;padding: 15px;">Test Payment Gateway</h2>
        @endif
        {{-- <style>#payment-form{width:31.5rem;margin:0 auto}iframe{width:100%}.one-liner{display:flex;flex-direction:column}#pay-button{border:none;border-radius:3px;color:#FFF;font-weight:500;height:40px;width:100%;background-color:#fb4400;box-shadow:0 1px 3px 0 rgba(248, 155, 49, 0.4)}#pay-button:active{background-color:#0B2A49;box-shadow:0 1px 3px 0 rgba(19,57,94,0.4)}#pay-button:hover{background-color:#ff642c;box-shadow:0 2px 5px 0 rgba(19,57,94,0.4)}#pay-button:disabled{background-color:#ff9269;box-shadow:none}#pay-button:not(:disabled){cursor:pointer}.card-frame{border:solid 1px #13395E;border-radius:3px;width:100%;margin-bottom:8px;height:40px;box-shadow:0 1px 3px 0 rgba(19,57,94,0.2)}.card-frame.frame--rendered{opacity:1}.card-frame.frame--rendered.frame--focus{border:solid 1px #13395E;box-shadow:0 2px 5px 0 rgba(19,57,94,0.15)}.card-frame.frame--rendered.frame--invalid{border:solid 1px #D96830;box-shadow:0 2px 5px 0 rgba(217,104,48,0.15)}.success-payment-message{color:#13395E;line-height:1.4}.token{color:#b35e14;font-size:0.9rem;font-family:monospace}@media screen and (min-width: 31rem){.one-liner{flex-direction:row}.card-frame{width:318px;margin-bottom:0}#pay-button{width:175px;margin-left:8px}}</style> --}}

        <div class="container container-padding">
            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="checkout_class">
                        <!-- add frames script -->
                        <script src="https://cdn.checkout.com/js/framesv2.min.js"></script>

                        <form id="payment-form" method="POST" action="https://merchant.com/charge-card" class="card p-4">
                            <label for="card-number">{{__('education.Card number')}}</label>
                            <div class="input-container card-number">
                                <div class="icon-container">
                                    <img
                                        id="icon-card-number"
                                        src="{{CustomAsset('images/card.svg')}}"
                                        alt="PAN"
                                    />
                                </div>
                                <div class="card-number-frame"></div>
                                <div class="icon-container payment-method">
                                    <img id="logo-payment-method" />
                                </div>
                                <div class="icon-container">
                                    <img id="icon-card-number-error" src="{{CustomAsset('images/error.svg')}}" />
                                </div>
                            </div>

                            <div class="date-and-code">
                                <div>
                                <label for="expiry-date">{{__('education.Expiry date')}}</label>
                                <div class="input-container expiry-date">
                                    <div class="icon-container">
                                        <img
                                            id="icon-expiry-date"
                                            src="{{CustomAsset('images/exp-date.svg')}}"
                                            alt="Expiry date"
                                        />
                                    </div>
                                    <div class="expiry-date-frame"></div>
                                    <div class="icon-container">
                                        <img
                                            id="icon-expiry-date-error"
                                            src="{{CustomAsset('images/error.svg')}}"
                                        />
                                    </div>
                                </div>
                                </div>

                                <div>
                                    <label for="cvv">{{__('education.Security code')}}</label>
                                    <div class="input-container cvv">
                                        <div class="icon-container">
                                            <img id="icon-cvv" src="{{CustomAsset('images/cvv.svg')}}" alt="CVV" />
                                        </div>
                                        <div class="cvv-frame"></div>
                                        <div class="icon-container">
                                            <img id="icon-cvv-error" src="{{CustomAsset('images/error.svg')}}" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input name="cardType" type="hidden" value="mada">

                            <button id="pay-button" disabled="">
                                {{__('education.PAY')}} {{ $total_after_vat }} {{__('education.SAR')}}
                            </button>

                            <div>
                                <span class="error-message error-message__card-number"></span>
                                <span class="error-message error-message__expiry-date"></span>
                                <span class="error-message error-message__cvv"></span>
                            </div>

                            <p class="success-payment-message"></p>
                        </form>

                        <script>
                            var lang = $('html').attr('lang');
                            var payButton = document.getElementById("pay-button");
                            var form = document.getElementById("payment-form");

                            var logos = generateLogos();
                            function generateLogos() {
                                var logos = {};
                                logos["card-number"] = {
                                    src: "card",
                                    alt: "card number logo",
                                };
                                logos["expiry-date"] = {
                                    src: "exp-date",
                                    alt: "expiry date logo",
                                };
                                logos["cvv"] = {
                                    src: "cvv",
                                    alt: "cvv logo",
                                };
                                return logos;
                            }

                            var errors = {};
                            errors["card-number"] = @json(__('education.Please enter a valid card number'));
                            errors["expiry-date"] = @json(__('education.Please enter a valid expiry date'));
                            errors["cvv"] = @json(__('education.Please enter a valid cvv code'));

                            let dir = $('html').attr('dir');
                            let align ='left'
                            if(dir == 'rtl'){
                                align = "right"
                            }

                            Frames.init({
                                    publicKey: '{{ env('CHECHOUT_PUBLIC_KEY') }}',
                                    localization: {
                                        cardNumberPlaceholder: @json(__('education.Card number')),
                                        expiryMonthPlaceholder: @json(__('education.MM')),
                                        expiryYearPlaceholder: @json(__('education.YY')),
                                        cvvPlaceholder: @json(__('education.CVV')),
                                    },
                                    style: {
                                    base: {
                                            textAlign: align,
                                        }
                                    },
                                    debug: false
                                });

                            Frames.addEventHandler(
                                Frames.Events.FRAME_VALIDATION_CHANGED,
                                onValidationChanged
                            );

                            function onValidationChanged(event) {
                                var e = event.element;
                                if (event.isValid || event.isEmpty) {
                                    if (e === "card-number" && !event.isEmpty) {
                                    showPaymentMethodIcon();
                                    }

                                    // setDefaultIcon(e);
                                    clearErrorIcon(e);
                                    clearErrorMessage(e);
                                } else {
                                    if (e === "card-number") {
                                    clearPaymentMethodIcon();
                                    }
                                    // setDefaultErrorIcon(e);
                                    setErrorIcon(e);
                                    setErrorMessage(e);
                                }
                            }

                            function clearErrorMessage(el) {
                                var selector = ".error-message__" + el;
                                var message = document.querySelector(selector);
                                message.textContent = "";
                            }

                            function clearErrorIcon(el) {
                                var logo = document.getElementById("icon-" + el + "-error");
                                logo.style.removeProperty("display");
                            }

                            function showPaymentMethodIcon(parent, pm) {
                                if (parent) parent.classList.add("show");

                                var logo = document.getElementById("logo-payment-method");
                                if (pm) {
                                    var name = pm.toLowerCase();
                                    // logo.setAttribute("src", "http://127.0.0.1:8000/images/" + name + ".svg");
                                    logo.setAttribute("src", @json(CustomAsset('images')) +'/' + name + ".svg");
                                    console.log(@json(CustomAsset('images/')))
                                    logo.setAttribute("alt", pm || "payment method");
                                }
                                logo.style.removeProperty("display");
                            }

                            function clearPaymentMethodIcon(parent) {
                                if (parent) parent.classList.remove("show");

                                var logo = document.getElementById("logo-payment-method");
                                logo.style.setProperty("display", "none");
                            }

                            function setErrorMessage(el) {
                                var selector = ".error-message__" + el;
                                var message = document.querySelector(selector);
                                message.textContent = errors[el];
                            }

                            // function setDefaultIcon(el) {
                            //   var selector = "icon-" + el;
                            //   var logo = document.getElementById(selector);
                            //   logo.setAttribute("src", "images/card-icons/" + logos[el].src + ".svg");
                            //   logo.setAttribute("alt", logos[el].alt);
                            // }

                            // function setDefaultErrorIcon(el) {
                            //   var selector = "icon-" + el;
                            //   var logo = document.getElementById(selector);
                            //   logo.setAttribute("src", "images/card-icons/" + logos[el].src + "-error.svg");
                            //   logo.setAttribute("alt", logos[el].alt);
                            // }

                            function setErrorIcon(el) {
                                var logo = document.getElementById("icon-" + el + "-error");
                                logo.style.setProperty("display", "block");
                            }

                            Frames.addEventHandler(
                                Frames.Events.CARD_VALIDATION_CHANGED,
                                cardValidationChanged
                            );
                            function cardValidationChanged() {
                                payButton.disabled = !Frames.isCardValid();
                            }

                            Frames.addEventHandler(
                                Frames.Events.CARD_TOKENIZATION_FAILED,
                                onCardTokenizationFailed
                            );
                            function onCardTokenizationFailed(error) {
                                console.log("CARD_TOKENIZATION_FAILED: %o", error);
                                Frames.enableSubmitForm();
                            }

                            Frames.addEventHandler(Frames.Events.CARD_TOKENIZED, onCardTokenized);
                            function onCardTokenized(event) {
                                var el = document.querySelector(".success-payment-message");
                                // el.innerHTML =
                                //     "Card tokenization completed<br>" +
                                //     'Your card token is: <span class="token">' +
                                //     event.token +
                                //     "</span>";

                                el.innerHTML = @json(__('education.Payment is running, please wait'));
                                $('#pay-button').prop('disabled', true);

                                    let t = event.token;
                                    let b = event.bin;
                                    // alert(t);

                                    $.ajax({
                                        url: "{{route('epay.cartPayment.post', [$user->id, $master_id])}}",
                                        type: 'POST',
                                        data: {
                                            "token": t,
                                            "_token": '{{ csrf_token() }}',
                                            "bin": b,
                                        },
                                        success: function (data)
                                        {
                                            console.log(data);
                                        //   console.log(data['status']);

                                            location.replace(data['url']);

                                        },error: function (rej)
                                        {
                                            console.log(rej);
                                        }
                                    });
                            }

                            Frames.addEventHandler(
                                Frames.Events.PAYMENT_METHOD_CHANGED,
                                paymentMethodChanged
                            );
                            function paymentMethodChanged(event) {
                                var pm = event.paymentMethod;
                                let container = document.querySelector(".icon-container.payment-method");

                                if (!pm) {
                                    clearPaymentMethodIcon(container);
                                } else {
                                    clearErrorIcon("card-number");
                                    showPaymentMethodIcon(container, pm);
                                }
                            }

                            form.addEventListener("submit", onSubmit);
                            function onSubmit(event) {
                                event.preventDefault();
                                Frames.submitCard();
                            }

                        </script>
                    </div>

                    <?php
                    $countryID = GetCountryID();
                    $coinID = GetCoinId();
                    $currency_code = 'SAR';
                    if($coinID != 334)
                        $currency_code = 'USD';
                    ?>

                    @if ($coinID != 334)
                        <div class="paypal_class card mt-4">
                            <div style="margin: 15px auto;background: #026DB9;padding: 10px 0;width: 88% !important;text-align: center;border-radius: 3px;" id="paypal2-button-container">
                                <a class="pay2_button" href="{{route('epay.cartPayment.paypalByCheckout', [$user->id, $master_id])}}" style="border: none;border-radius: 3px;color: #fff;font-weight: 500;height: 40px;width: 100%;">{{__('education.PAY')}} {{ $total_after_vat }} {{__('education.SAR')}} {{__('education.with')}} <span style="font-weight: 600;font-size: 15px;font-style: italic;">PayPal</span></a>
                            </div>
                        </div>

                        {{-- <div class="paypal_class card mt-4" style="width: 60%; margin: 0 auto;">
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
                                    description: '{{$items}}',
                                    amount: {
                                        value: '{{$total_after_vat}}',
                                        currency_code:'USD',
                                    }
                                }]
                                });
                            },

                            onApprove: function(data, actions) {
                                // This function captures the funds from the transaction.
                                return actions.order.capture().then(function(details) {

                                    window.location.href = "{{route('epay.cartPayment.paypal')}}?user_id={{ $user->id }}&paid_in={{$total_after_vat}}";

                                });
                            }
                            }).render('#paypal-button-container');
                            //This function displays Smart Payment Buttons on your web page.

                            </script>
                        </div> --}}
                    @endif

                </div>

                <div class="col-md-4">
                    @include(FRONT.'.education.products.components.summary.cart-order-summary-payment', ['cartMasters'=>$cartMasters])
                </div>
            </div>
        </div>
    </div>
@endsection


