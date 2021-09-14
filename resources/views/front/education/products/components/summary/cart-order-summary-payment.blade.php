<?php
$vat = $vat_value = $courses_price = $subtotal = $exam_price = $discount = $total_after_vat = 0;

//dd($cartMaster->carts()->where('payment_status', '!=', 68)->get());
foreach ($cartMasters as $cartMaster) {
    foreach ($cartMaster->carts as $cart) {
        $courses_price += $cart->price;
        $discount += $cart->discount_value;
        // $exam_price += $cart->exam_price;
        $vat = $cart->vat;
        $vat_value += $cart->vat_value;// + $cart->cartFeatures->sum('vat_value');
        // dump($cart->total);
        $subtotal += $cart->total;// + $cart->cartFeatures->sum('price');
        $total_after_vat += ($cart->total) + ($cart->vat_value);
        // $total_after_vat += ($cart->total + $cart->cartFeatures->sum('price')) + ($cart->vat_value + $cart->cartFeatures->sum('vat_value'));
    }
    // $retrieved_value = $cartMaster->retrieved_value;
    // $total_after_vat -= $retrieved_value;
}
$total_after_vat_ceil = ceil($total_after_vat);
if(round($total_after_vat_ceil-$total_after_vat, 2)==0.01){
    $total_after_vat = $total_after_vat_ceil;
    $vat_value += 0.01;
}
?>
<div class="order-summary-sidebar">
    <div class="card mt-5 mt-md-0">
        <div class="card-header bg-secondary text-white">
            <h5 class="m-0">{{__('education.Order Summary')}}</h5>
        </div>
        <div class="card-body py-2">

            <div class="row">
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total h5 boldfont">{{__('education.Price')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin h5">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o sub-total">{{NumberFormatWithComma($courses_price)}}</span>
                    </div>
                </div>
            </div>

            <hr>

            @if($discount != 0)
            <div class="row">
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total boldfont">{{__('education.Promo Discount')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin ">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o sub-total">-{{NumberFormatWithComma($discount)}}</span>
                    </div>
                </div>
            </div>

            <hr>
            @endif
{{--
            @if($exam_price != 0)
            <div class="row">
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total boldfont">{{__('training.exam_price')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin ">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o sub-total">{{NumberFormatWithComma($exam_price)}}</span>
                    </div>
                </div>
            </div>

            <hr>
            @endif --}}


            @foreach ($cartMasters as $cartMaster)
                @foreach ($cartMaster->carts as $cart)
                    @if(isset($cart->cartFeatures))
                        @forelse ($cart->cartFeatures as $feature)
                        <div class="row">
                            <div class="col-8 col-lg-8">
                                <span class="subheadline-primary">{{$feature->trainingOptionFeature->feature->trans_title}}</span>
                            </div>
                            <div class="col-4 col-lg-4 text-right">
                                {{__('education.SAR')}}
                                <span class="text-secondary-o take2_price_span">{{NumberFormatWithComma($feature->price)}}</span>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                    @endif
                @endforeach
            @endforeach

            @if ($vat != 0)
            <div class="row">
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total h5 boldfont">{{__('education.Sub Total')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin h5">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o sub-total">{{NumberFormatWithComma($subtotal)}}</span>
                    </div>
                </div>
            </div>

            <hr>


            <div class="row vat-container">
                <div class="col-5">
                    <span class="subheadline-primary order-vat">
                        {{__('education.VAT')}} (<span>{{$vat}}</span>%)
                    </span>
                </div>
                <div class="col-7 text-right">
                    <div class="order-summary-bottom-margin order-vat-value">{{__('education.SAR')}}
                        <span>{{NumberFormatWithComma($vat_value)}}</span>
                        {{-- <span>{{NumberFormatWithComma($cart->vat_value)}}</span> --}}
                    </div>
                </div>
            </div>
            <hr>
            @endif

            {{-- @if($retrieved_value != 0)
            <div class="row">
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total boldfont">{{__('education.Retrieved Value')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin ">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o sub-total">-{{NumberFormatWithComma($retrieved_value)}}</span>
                    </div>
                </div>
            </div>

            <hr>
            @endif --}}


            <div class="row">
                <div class="col-12 card-disclaimer">
                    <div class="row main-color">
                        <div class="col-5">
                            <div class="order-summary-bottom-margin">
                                <span class="subheadline-primary order-summary-total boldfont h4">{{__('education.Total')}}</span>
                            </div>
                        </div>
                        <div class="col-7 text-right">
                            <div class="order-summary-bottom-margin h5">
                                {{__('education.SAR')}} <span class="text-secondary-o order-total">
                                    {{NumberFormatWithComma($total_after_vat)}}
                                    {{-- {{NumberFormatWithComma($cart->total_after_vat)}} --}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->
</div>
