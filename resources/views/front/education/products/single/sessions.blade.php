{{-- @foreach($sessions as $session) --}}

<div class="bg-light py-5" id="trainig-schedule">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-8">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="m-0">{{__('education.Online Training Schedule')}}</h2>
                </div>
                <div class="course-schedule-slider owl-carousel owl-theme">
                    {{-- @dd($sessions) --}}
                    @foreach($sessions->whereNotNull('session_id')->where('option__post_type', 'online-training') as $session)
                    <div class="course-schedule-card">
                        <?php
                            $SessionHelper->SetCourse($session);
                            $subTotal = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT();
                            $Discount = $SessionHelper->Discount();
                            $main_price_before_discount_with_vat = $SessionHelper->PriceWithExamPriceAfterVAT();
                        ?>
                        <div class="card" session_id="{{$session->session_id}}">
                            <div class="card-body p-5">
                                <div class="mb-4 item">
                                    <i class="fas fa-check"></i>
                                    <strong class="boldfont">{{__('education.Price')}}</strong>
                                    <p class="m-0">
                                        <?php $subTotal = $SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT(); ?>
                                        <strong>{{NumberFormatWithComma($subTotal, 2)}}
                                        <small>{{__('education.SAR')}}</small></strong>

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
                                </div>
                                <div class="mb-4 item">
                                    <i class="fas fa-check"></i>
                                    <strong class="boldfont">{{__('education.Date')}}</strong>
                                    <p class="m-0">
                                        <span>{{App\Helpers\Date::IsoFormat($session->session_date_from)}}</span> -
                                        <span>{{App\Helpers\Date::IsoFormat($session->session_date_to)}}</span>
                                    </p>
                                </div>
                                <div class="mb-4 item">
                                    <i class="fas fa-check"></i>
                                    <strong class="boldfont">{{__('education.Duration')}}</strong>
                                    <p class="m-0">{{$SessionHelper->SessionDuration()}}</p>
                                </div>
                                <div class="mb-4 item timeltr">
                                    <i class="fas fa-check"></i>

                                    <strong class="boldfont">{{__('education.Time')}}</strong>
                                    {!! $session->session_time !!}
                                    @if ($session->except_fri_sat == 1)
                                        <p class="except_fri_sat">{{__('admin.except_fri_sat')}}</p>
                                    @endif
                                </div>
                                <div class="text-center">
                                    {{--<a href="{{route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id])}}" class="btn btn-primary btn-lg">{{__('education.Buy Now')}}</a>--}}
                                    @guest
                                        <a class="btn btn-secondary" name="session_id" href="{{route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id])}}">{{__('education.Register')}}</a>
                                    @endguest
                                    <button class="btn btn-primary" @click="addCartItem('{{$session->session_id}}', 'training_option')" :disabled="chackIfExistCart('{{$session->session_id}}')">{{__('education.Add to Cart')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div> <!-- /.course-schedule-slider -->
{{--                @endforeach--}}
            </div>
        </div>
    </div>
</div> <!-- #trainig-schedule -->
{{-- @endforeach --}}
