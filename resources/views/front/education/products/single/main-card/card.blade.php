<?php
$SessionHelper->SetCourse($CardsSingle);
$subTotal = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();
$Discount = $SessionHelper->Discount();
$main_price_before_discount_with_vat = $SessionHelper->PriceWithExamPriceAfterVAT();
?>
<div class="course-info-box-mobile {{($CardsSingle->constant_id == 13) ? 'mobile-online-price' : 'mobile-self-price'}}">
    <div class="d-flex w-100">
        <p class="price">
            <span class="boldfont">
                @if($subTotal!=0)

                    @if($subTotal!=0)
                        {{NumberFormatWithComma($subTotal)}}
                        <small>{{__('education.SAR')}}</small>
                    @endif

                @endif
            </span>
            <br>
            @if ($Discount > 0)
                @if($main_price_before_discount_with_vat!=0)
                    <span class="mx-1 text-secondary" style="text-decoration:line-through;">
                        {{NumberFormatWithComma($main_price_before_discount_with_vat)}} {{__('education.SAR')}}
                    </span>
                @endif
            @endif

        </p>
        <a href="{{route('education.courses.register', ['slug'=>$CardsSingle->slug])}}/?session_id={{$CardsSingle->session_id}}" class="btn btn-secondary btn-block my-2">{{__('education.Buy Now')}}</a>
    </div>
</div>

{{-- @dd($CardsSingle) --}}
{{-- <div {{$CardsSingle->option__post_type == 'exam-simulator' ? 'style=display:block' : ''}} id="{{($CardsSingle->constant_id == 13) ? 'online-training-card' : 'self-study-card'}}"> --}}
<div {{$active == 'active' ? 'style=display:block;' : 'style=display:none;'}} id="{{($CardsSingle->constant_id == 13) ? 'online-training-card' : 'self-study-card'}}">

    <p class="price">
        <span class="boldfont">
            @if($subTotal!=0)
                {{NumberFormatWithComma($subTotal)}}
                <small>{{__('education.SAR')}}</small>
            @endif
        </span>

        @if ($Discount > 0)
            @if($main_price_before_discount_with_vat!=0)
                <small class="mx-1 text-secondary" style="text-decoration:line-through;">
                    {{NumberFormatWithComma($main_price_before_discount_with_vat)}} {{__('education.SAR')}}
                </small>
            @endif
            <small class="bg-primary px-1 text-white d-inline-block">
                @if($Discount==100)
                    {{__('education.free_discount')}}
                @else
                {{(int)$Discount??null}} {{__('education.percentage_discount')}}
                @endif
            </small>
        @endif

    </p>

    @include(FRONT.'.education.products.components.exam-included', [
        'exam_is_included'=>$CardsSingle->exam_is_included,
        'exam_price'=>$SessionHelper->Price()??null,
    ])

    {{-- @if(isset($discountOBJ->end_date)) --}}
    @if(!is_null($SessionHelper->DiscountEndDate()))
        <p class="days-left d-none">
            <i class="far fa-clock fa-2x main-color"></i>
            {{ $SessionHelper->DiscountEndDate() }}
        </p>
    @endif
    {{-- @endif --}}

    @include(FRONT.'.education.products.components.register', ['course'=>$CardsSingle])

    @include(FRONT.'.education.products.components.Guarantee', ['course'=>$CardsSingle])

    @if ($CardsSingle->option_slug != "custom-date")
        <div class="row session-card-details mt-3">
            <div class="col-6 col-md-5 mb-4 mb-md-0">
                <b>{{__('education.Nearset Session')}}:</b>
                <span>{{App\Helpers\Date::IsoFormat($CardsSingle->session_date_from)}}</span>
            </div>
            <div class="col-6 col-md-4 mb-4 mb-md-0">
                <b>{{__('education.Duration')}}:</b>
                <span>{{$SessionHelper->SessionDuration()}}</span>
            </div>
            <div class="col-6 col-md-3 mb-4 mb-md-0">
                <b>{{__('education.PDUs')}}:</b>
                <span>{{$SessionHelper->PDUs()}}</span>
            </div>
        </div>
    @endif

    <hr>
    {!! App\Helpers\Lang::TransTitle($CardsSingle->options_details) !!}

    <hr>
    <div class="card-text">
        <?php
        $detail = App\Models\Admin\Detail::where('constant_id', 314)
        ->where('detailable_type', 'App\Models\Training\TrainingOption')
        ->where('detailable_id', $CardsSingle->training_option_id)
        ->first();
        ?>
        <h4>{{$detail->constant->trans_name??null}}</h4>
        {!! $detail->trans_details??null !!}
        <p class="text-center mt-4 mb-0"><a href="{{route('education.for-corporate')}}" class="btn btn-outline-secondary">{{ __('education.Register Group') }}</a></p>
    </div>

</div>
