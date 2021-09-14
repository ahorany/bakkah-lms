<?php
$visible = 'hide';
$take2_price = 0;
?>
<div class="order-summary-sidebar">
    <div class="card mb-5 mb-md-0">
        <div class="card-header bg-secondary text-white">
            <h5 class="m-0">{{__('education.Order Summary')}}</h5>
        </div>
        <div class="card-body py-2">
            <ul id="orderSummaryList" class="list-unstyled summary">

                <li>
                    <div class="row no-gutters">
                        <div class="col-5">
                            <span class="itemName subheadline-primary">{{__('education.Course Price')}}</span>
                        </div>
                        <div class="col-7 text-right itemPrice">{{__('education.SAR')}}
                            <span class="itemPrice text-secondary-o course-price">
                                @{{price}}
                                <input type="hidden" :value="price" name="price">
                                {{-- {{NumberFormatWithComma($SessionHelper->Price())}} --}}
                            </span>
                        </div>
                    </div>
                </li>

                <li style="background: #f9f9f9;padding: 15px;">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>{{__('education.Promo Code')}}</label><br>
                            <input type="text" id="promo_code" v-on:blur="PromoCodeFns" v-on:keypress.enter="PromoCodeFns" name="promo_code" v-model="PromoCode" class="form-control" placeholder="{{__('education.Promo Code')}}">
                            @error('promo_code')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </li>

                <li id="promo_discount" style="display:block" v-if="DiscountValue!=0">
                    <div class="row no-gutters main-color">

                        <div class="col-7 col-lg-7">
                            <span class="subheadline-primary">
                                {{__('education.Promo Discount')}}
                                -@{{Discount}}%
                                <input type="hidden" :value="DiscountId" name="DiscountId">
                                <input type="hidden" :value="Discount" name="Discount">
                            </span>
                        </div>
                        <div class="col-5 col-lg-5 text-right">
                            {{__('education.SAR')}}
                            <span class="text-secondary-o order-discount-value">
                                -@{{DiscountValue}}
                                <input type="hidden" :value="DiscountValue" name="DiscountValue">
                                {{-- -{{NumberFormatWithComma($SessionHelper->DiscountValue())}} --}}
                            </span>
                        </div>

                    </div>
                </li>

                {{-- <li class="show_foundation" v-if="ExamIsIncluded!=0">
                    <div class="row no-gutters">
                        <div class="col-7 col-lg-7">
                            <span style="margin-top: 4px;display: inline-block;">
                                {{__('education.Foundation Exam')}}
                            </span>
                        </div>
                        <div class="col-5 col-lg-5 text-right" style="margin-top: 4px;">{{__('education.SAR')}}
                            <span class="text-secondary-o foundation-price">
                                @{{ExamPrice}}
                                <input type="hidden" :value="ExamPrice" name="ExamPrice">
                            </span>
                        </div>
                    </div>
                </li> --}}
                <input type="hidden" :value="ExamPrice" name="ExamPrice">

                {{-- <input type="checkbox" id="john" value="100" v-model="checkedNames">
                    @{{checkedNames[0]}} --}}

                @if ($trainingOptionFeatures->count() > 0)
                <li>
                    @forelse ($trainingOptionFeatures as $option)
                        @if ($option['is_include'])
                            <label class="bg-trans d-flex justify-content-between m-0">
                                <span class="d-flex align-items-center">
                                    <span style="font-size: 26px;width: 30px;"><i class="fas fa-plus-square"></i></span>
                                    <span>{{ $option['title'] }}</span>
                                </span>
                                <b>{{ $option['price'] }} @{{ currency }} </b>
                            </label>
                        @else
                            <?php $left_padding=app()->getLocale()=='en'?30:0; ?>
                            <label class="chk_container d-flex justify-content-between mb-3" style="padding-left:{{$left_padding}}px;">

                                <span>{{ $option['title'] }}</span>
                                <b>{{ $option['price'] }} @{{ currency }} </b>

                                <input type="checkbox" name="trainingOptionFeatures[{{ $option['id'] }}]" @click="addTrainingOption('{{ $option['price'] }}', $event)" value="{{ $option['price'] }}" {{ old("trainingOptionFeatures." . $option['id']) == $option['price'] ? 'checked' : '' }}> <span class="checkmark"></span>
                            </label>
                        @endif
                    @endforeach
                </li>
                @endif
                {{-- @if($SessionHelper->ExamIsIncluded()==0 && $SessionHelper->SessionExamPrice()>0)
                <li>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>{{__('education.Exam Voucher')}}</label><br>
                            <select name="exam_price" class="form-control" v-model="ExamIsIncluded">
                                <option value="0" {{old('exam_price')==0?'selected="selected"':''}}>{{__('education.choose')}}</option>
                                <option value="{{$SessionHelper->ExamPrice(1)}}" {{old('exam_price')!=0?'selected="selected"':''}}>{{__('education.Add Exam Voucher')}}</option>
                                <option value="0" {{old('exam_price')==2?'selected="selected"':''}}>{{__('education.No Exam Voucher')}}</option>
                            </select>
                        </div>
                    </div>
                </li>
                @endif --}}

                {{-- <li style="display: none" v-if="Take2PriceCheck!=0">
                    <div class="form-group">
                        <label>{{__('education.Take2 Option')}}</label>
                        <small style="color: #fb4400">{{__('education.New')}}</small>
                        <img width="15" src="{{CustomAsset('images/question-mark.png')}}" alt="{{__('education.Question mark')}}" alt="{{__('education.question_mark')}}" data-toggle="tooltip" data-placement="top" data-html="true" title="{{__('education.question_mark_details')}}"> <br>
                        @php $constants = \App\Constant::where('parent_id', 59)->orderBy('order')->get(); @endphp
                        <select :disabled="CheckTake2" name="take2_option" class="form-control" v-model="Take2PriceOption">
                            <option selected value="0">{{__('education.choose')}}</option>
                            @foreach($constants as $constant)
                                <option value="{{$constant->id==61?NumberFormatWithComma($session->take2_price):0}}" {{(old('take2_option')==$constant->id)?'selected="selected"':''}}>
                                    {{$constant->trans_name}} {{($constant->id==61)? '('.NumberFormatWithComma($session->take2_price).__('education.SAR').')' :''}}</option>
                            @endforeach
                        </select>
                    </div>
                </li> --}}

                {{-- <li class="d-flex justify-content-between" v-if="ExamSimulation">
                    <label class="chk_container">{{__('education.Exam Simulation')}} <small style="color: #fb4400">{{__('education.New')}}</small> <img width="15" src="{{CustomAsset('images/question-mark.png')}}" alt="{{__('education.Question mark')}}" alt="{{__('education.question_mark')}}" data-toggle="tooltip" data-placement="top" data-html="true" title="{{__('education.exam_simulation_details')}}">
                        <input type="checkbox" name="exam_simulation_option"  {{ old('exam_simulation_option') ? 'checked' : '' }} :value="ExamSimulation" @click="checkExamSimulation($event)">
                        <span style="zoom: .8;" class="checkmark"></span>
                    </label>
                    <div v-if="ExamSimulationPrice!=0">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o take2_price_span">@{{ExamSimulationPrice}}</span>
                        <input type="hidden" :value="ExamSimulationPrice" name="ExamSimulationPrice">
                    </div>
                </li> --}}

                {{-- <li id="take2_option_fees_li_foundation11" v-if="Take2Price!=0">
                    <div class="row no-gutters">
                        <div class="col-8 col-lg-8">
                            <span class="subheadline-primary">{{__('education.Foundation Exam Take2')}}</span>
                        </div>
                        <div class="col-4 col-lg-4 text-right">
                            {{__('education.SAR')}}
                            <span class="text-secondary-o take2_price_span">@{{Take2Price}}</span>
                            <input type="hidden" :value="Take2Price" name="Take2Price">
                        </div>
                    </div>
                </li> --}}

            </ul>

            <div class="row no-gutters" v-show="VAT!=0">
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total h5 boldfont">{{__('education.Sub Total')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin h5">
                        {{__('education.SAR')}}
                        <?php //$subTotal = $SessionHelper->Price(); ?>
                        {{-- <span class="text-secondary-o sub-total">{{NumberFormatWithComma($subTotal)}}</span> --}}
                        <span class="text-secondary-o sub-total">@{{subtotal}}</span>
                        <input type="hidden" :value="subtotal" name="subtotal">
                    </div>
                </div>
            </div>

            <hr v-show="VAT!=0">
            <div class="row no-gutters vat-container" v-show="VAT!=0">
                <div class="col-5">
                    <span class="subheadline-primary order-vat">
                        {{__('education.VAT')}} (<span>@{{VAT}}</span>%)
                        <input type="hidden" :value="VAT" name="VAT">
                        {{-- {{__('education.VAT')}} (<span>{{$SessionHelper->VAT()}}</span>%) --}}
                    </span>
                </div>
                <div class="col-7 text-right">
                    <div class="order-summary-bottom-margin order-vat-value">{{__('education.SAR')}}
                        <span>@{{VATVALUE}}</span>
                        <input type="hidden" :value="VATVALUE" name="VATVALUE">
                        {{-- <span>{{NumberFormatWithComma($SessionHelper->VATFortPriceWithExamPrice())}}</span> --}}
                    </div>
                </div>
            </div>

            <hr>
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
                                    @{{PriceAfterDiscountWithExamPriceAfterVAT}}
                                    <input type="hidden" :value="PriceAfterDiscountWithExamPriceAfterVAT" name="PriceAfterDiscountWithExamPriceAfterVAT">
                                    {{-- {{NumberFormatWithComma($SessionHelper->VATFortPriceWithExamPrice())}} --}}
                                </span>

                                @if($SessionHelper->ExamIsIncluded()==1)
                                    <br>
                                    <label class="bg-primary text-white" style="margin-top: 10px;padding: 5px 10px;font-size: 15px;border-radius: 5px;">{{__('education.Exam Included')}}</label>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <hr>

            <div class="row">

                <div class="col-lg-12">
                    <h6 class="main-color mb-2" id="balanace_label">{{__('education.Already have a balance?')}}</h6>
                    <input style="display: none" type="text" id="retrieved_code" v-on:blur="RetrivedCodeFun" v-on:keypress.enter="RetrivedCodeFun" name="retrieved_code" v-model="RetrivedCode" class="form-control" placeholder="{{__('education.Verefication Code')}}">
                    <input type="hidden" name="ValidRetrievedCode" :value="ValidRetrievedCode" />
                    <small id="retrieved_code_error" style="display: none" class="text-danger">{{ __('formerrors.The email field is required.') }}</small>
                    @error('retrieved_code')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

            </div>

            <div class="row no-gutters" v-show="Balance!=0">
                <div class="col-12"><hr></div>
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total h6 boldfont">{{__('education.Your Balance')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin h6">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o sub-total">@{{Balance}}</span>
                        <input type="hidden" name="Balance" :value="Balance" />
                    </div>
                </div>
            </div>

            <div class="row no-gutters" v-show="Balance!=0">
                <div class="col-12"><hr></div>
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total h6 boldfont">{{__('education.Remain to Pay')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin h6">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o sub-total">@{{PaymentRemaining}}</span>
                        <input type="hidden" name="PaymentRemaining" :value="PaymentRemaining" />
                    </div>
                </div>
            </div>

            <div class="row no-gutters" v-show="Balance!=0">
                <div class="col-12"><hr></div>
                <div class="col-6">
                    <div class="order-summary-bottom-margin">
                        <span class="subheadline-primary order-summary-total h6 boldfont">{{__('education.Your Remaining')}}</span>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <div class="order-summary-bottom-margin h6">
                        {{__('education.SAR')}}
                        <span class="text-secondary-o sub-total">@{{Remaining}}</span>
                        <input type="hidden" name="Remaining" :value="Remaining" />
                    </div>
                </div>
            </div> --}}

        </div> <!-- /.card-body -->
    </div> <!-- /.card -->
</div>
