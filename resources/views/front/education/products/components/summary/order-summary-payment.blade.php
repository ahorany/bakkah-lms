{{-- @dd($cart) --}}
<div class="order-summary-sidebar">
    <div class="card mt-5 mt-md-0">
        <div class="card-header bg-secondary text-white">
            <h5 class="m-0">{{__('education.Order Summary')}}</h5>
        </div>
        <div class="card-body py-2">
            <ul id="orderSummaryList" class="list-unstyled summary">
                <li>
                    <div class="row">
                        <div class="col-5">
                            <span class="itemName subheadline-primary">{{__('education.Course Price')}}</span>
                        </div>
                        <div class="col-7 text-right itemPrice">{{__('education.SAR')}}
                            <span class="itemPrice text-secondary-o course-price">
                                {{NumberFormatWithComma($cart->price)}}
                            </span>
                        </div>
                    </div>
                </li>

                @if($cart->discount_value!=0 && $cart->discount >= $cart->retarget_discount)
                    <li id="promo_discount" style="display:block">
                        <div class="row main-color">
                            <div class="col-8 col-lg-8">
                                <span class="subheadline-primary">
                                    {{__('education.Promo Discount')}}
                                    -{{$cart->discount}}%
                                    {{-- {{$cart->discountMethod->trans_excerpt}}
                                    @if($cart->discountMethod->type->slug=='percentage')
                                    @endif --}}
                                </span>
                            </div>
                            <div class="col-4 col-lg-4 text-right">
                                {{__('education.SAR')}}
                                <span class="text-secondary-o order-discount-value">
                                    -{{NumberFormatWithComma($cart->discount_value)}}
                                </span>
                            </div>
                        </div>
                    </li>
                @endif

                <?php $retarget_discount_value = 0; ?>
                @if($cart->retarget_email_id!=361 && $cart->retarget_email_id!=340 && $cart->discount < $cart->retarget_discount)

                    @if($cart->retarget_discount!=0 && $cart->retarget_discount >= $cart->discount)
                    <li id="promo_discount" style="display:block">
                        <div class="row main-color">
                            <div class="col-8 col-lg-8">
                                <span class="subheadline-primary">
                                    {{__('education.Retarget Discount')}}
                                    -{{$cart->retarget_discount}}%
                                </span>
                            </div>
                            <div class="col-4 col-lg-4 text-right">
                                {{__('education.SAR')}}
                                <span class="text-secondary-o order-discount-value">
                                    <?php
                                    $retarget_discount_value = ($cart->price * $cart->retarget_discount) / 100;
                                    ?>
                                    -{{NumberFormatWithComma($retarget_discount_value)}}
                                </span>
                            </div>
                        </div>
                    </li>
                    @endif
                @endif

                @if($cart->exam_price!=0)
                <li class="show_foundation">
                    <div class="row">
                        <div class="col-7 col-lg-7">
                            <span style="margin-top: 4px;display: inline-block;">
                                {{__('education.Foundation Exam')}}
                            </span>
                        </div>
                        <div class="col-5 col-lg-5 text-right" style="margin-top: 4px;">{{__('education.SAR')}}
                            <span class="text-secondary-o foundation-price">
                                {{NumberFormatWithComma($cart->exam_price)}}
                            </span>
                        </div>
                    </div>
                </li>
                @endif

                @forelse ($cart->cartFeatures as $feature)
                <li id="take2_option_fees_li_foundation11">
                    <div class="row">
                        <div class="col-8 col-lg-8">
                            <span class="subheadline-primary">{{$feature->trainingOptionFeature->feature->trans_title}}</span>
                        </div>
                        <div class="col-4 col-lg-4 text-right">
                            {{__('education.SAR')}}
                            <span class="text-secondary-o take2_price_span">{{NumberFormatWithComma($feature->price)}}</span>
                        </div>
                    </div>
                </li>
                @endforeach

                @if($cart->take2_price!=0)
                    <li id="take2_option_fees_li_foundation11">
                        <div class="row">
                            <div class="col-8 col-lg-8">
                                <span class="subheadline-primary">{{__('education.Foundation Exam Take2')}}</span>
                            </div>
                            <div class="col-4 col-lg-4 text-right">
                                {{__('education.SAR')}}
                                <span class="text-secondary-o take2_price_span">{{NumberFormatWithComma($cart->take2_price)}}</span>
                            </div>
                        </div>
                    </li>
                @endif

                @if($cart->exam_simulation_price!=0)
                    <li id="take2_option_fees_li_foundation11">
                        <div class="row">
                            <div class="col-8 col-lg-8">
                                <span class="subheadline-primary">{{__('education.Exam Simulation')}}</span>
                            </div>
                            <div class="col-4 col-lg-4 text-right">
                                {{__('education.SAR')}}
                                <span class="text-secondary-o take2_price_span">{{NumberFormatWithComma($cart->exam_simulation_price)}}</span>
                            </div>
                        </div>
                    </li>
                @endif
            </ul>
            @if($cart->vat!=0)
            <div class="row">
                <div class="col-5">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total h5 boldfont">{{__('education.Sub Total')}}</span>
                    </div>
                </div>
                <div class="col-7 text-right">
                    <div class="order-summary-bottom-margin h5">
                        {{__('education.SAR')}}
                        <?php $subtotal = $cart->total - $retarget_discount_value; ?>
                        <span class="text-secondary-o sub-total">{{NumberFormatWithComma($subtotal)}}</span>
                    </div>
                </div>
            </div>

            <hr>
            <div class="row vat-container">
                <div class="col-5">
                    <span class="subheadline-primary order-vat">
                        {{__('education.VAT')}} (<span>{{$cart->vat}}</span>%)
                    </span>
                </div>
                <div class="col-7 text-right">
                    <div class="order-summary-bottom-margin order-vat-value">{{__('education.SAR')}}
                        <?php $vat_value = ($subtotal * $cart->vat) / 100; ?>
                        <span>{{NumberFormatWithComma($vat_value)}}</span>
                        {{-- <span>{{NumberFormatWithComma($cart->vat_value)}}</span> --}}
                    </div>
                </div>
            </div>
            <hr>
            @endif
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
                                    <?php $total_after_vat = $subtotal + $vat_value; ?>
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
