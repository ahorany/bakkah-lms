@foreach($all_sessions as $all_session)
<?php
    $_count = $sessions->where('id', $all_session['id'])->where('option_slug', 'auto-date')->count();
?>
<style>
    a {
        color: lightblue;
    }
    th {
        vertical-align: middle !important;
        font-weight: normal;
    }
    .self_exam tr:nth-child(2){
        background-color: #199999;
    }
    .self_exam_title{
        background-color: #199999;
        width: fit-content;
        padding: 2px 10px;
        color: white;
        border-radius: 5px;
        display: table;
    }
</style>
<div class="col-12">
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="m-0"><a href="{{route('education.courses.single', ['slug'=>$all_session['slug']])}}" class="course_name">{{App\Helpers\Lang::TransTitle($all_session['title'])}}</a></h5>
        </div>

        <div class="bg-light py-2" id="trainig-schedule">
            <div class="mx-4">

                {{-- ====================== Self or Exam Simulators ========================= --}}
                <div class="mb-2">
                    @foreach($sessions->where('id', $all_session['id'])
                    ->where('option_slug', 'custom-date') as $session)
                        {{-- <a href="{{route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id])}}" class="btn btn-primary">{{__('education.Register Now For')}} {{__('education.'.$session->option__post_type)}}</a> --}}

                        <h5 class="self_exam_title m-0">{{__('education.'.$session->option__post_type)}}</h5>

                        {{-- ============================== --}}
                        <table class="self_exam tc-table table table-striped table-responsive bg-light text-center border">

                            <thead class="bg-secondary text-white">
                            <tr style="background: #fb4400">
                                @if($coin_id==334)
                                    <th colspan="11" style="border-left: 2px solid #dee2e6;">{{__('education.SAR_currency')}}</th>
                                @else
                                    <th colspan="11" style="border-left: 2px solid #dee2e6;">{{__('education.USD')}}</th>
                                @endif
                            </tr>
                            <tr>
                                <th rowspan="2">{{__('education.Date')}}</th>
                                <th rowspan="2">{{__('education.Duration')}}</th>
                                <th rowspan="2">{{__('education.Time')}}</th>
                                <th rowspan="2">{{__('education.language')}}</th>
                                <th style="border-left: 2px solid #dee2e6;">{{__('admin.Course Price')}}</th>
                                <th>{{__('admin.discount')}}</th>
                                <th>{{__('admin.Exam Price')}}</th>
                                <th>{{__('admin.take2_price')}}</th>
                                <th>{{__('education.Sub Total')}}</th>
                                <th>{{__('admin.VAT')}}</th>
                                <th>{{__('admin.Total after VAT')}}</th>
                                {{-- <th rowspan="2" style="border-left: 2px solid #dee2e6;">{{__('education.Registration')}}</th> --}}
                            </tr>
                            </thead>
                            <tbody>

                                @if(!is_null($session->session_id))

                                {{$SessionHelper->SetCourse($session)}}
                                <tr data-id="{{$session->session_id}}">
                                    <td>
                                        <span>{{App\Helpers\Date::IsoFormat($session->session_date_from)}}</span> -
                                        <span>{{App\Helpers\Date::IsoFormat($session->session_date_to)}}</span>

                                        @include('training.training-schedule.registerBtn', compact('session'))

                                    </td>
                                    <td>{{$SessionHelper->SessionDuration()}}</td>
                                    <td>
                                        {!! $session->session_time !!}
                                        @if ($session->except_fri_sat == 1)
                                            {{__('admin.except_fri_sat')}}
                                        @endif
                                    </td>
                                    <td>{{App\Helpers\Lang::TransTitle($session->language_name)}}</td>

                                    <?php
                                        $Discount = $SessionHelper->Discount();
                                        $discount_value = $SessionHelper->DiscountValue();
                                        $vat_value = $SessionHelper->VATFortPriceWithExamPrice();
                                        $sub_total = $SessionHelper->PriceWithExamPrice();
                                    ?>

                                    <td>{{NumberFormatWithComma($session->session_price)}}</td>
                                    @if(empty($Discount))
                                        <td></td>
                                    @else
                                        <td>{{$session->discount_value}} %<br>-{{NumberFormatWithComma($discount_value)}}</td>
                                    @endif
                                    <td>
                                        {{NumberFormatWithComma($session->session_exam_price)}}
                                        @if($SessionHelper->ExamIsIncluded()==1)
                                            <br>
                                            <small class="fas fa-plus"></small>
                                            <small>{{__('education.Exam Included')}}</small>
                                        @endif
                                    </td>
                                    <td>{{NumberFormatWithComma($session->take2_price)}}
                                        @if($session->take2_price>0)
                                            <br>
                                            <small>Not Included</small>
                                        @endif
                                    </td>
                                    <td>{{NumberFormatWithComma($sub_total)}}</td>
                                    <td>{{NumberFormatWithComma($vat_value)}}</td>

                                    <td>{{NumberFormatWithComma($SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT())}}
                                    <?php

                                    $main_price_before_discount_with_vat = $SessionHelper->PriceWithExamPriceAfterVAT();
                                    ?>
                                    @if ($Discount > 0)
                                    <br>
                                        @if($main_price_before_discount_with_vat!=0)
                                            <small class="mx-1 text-secondary" style="text-decoration:line-through;">
                                                {{NumberFormatWithComma($main_price_before_discount_with_vat)}}
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
                                    </td>
                                    {{-- <td>
                                        <a class="btn btn-primary mb-1" name="session_id" href="{{route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id, 'coin_id'=>$coin_id])}}">{{__('education.Register')}}</a>
                                    </td> --}}
                                </tr>

                                @endif
                            </tbody>
                        </table>

                    @endforeach
                </div>
                {{-- ====================== End of Self or Exam Simulators ========================= --}}

                {{-- $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ --}}

                {{-- ======================  Online Training Schedule ============================== --}}

                <?php
                // $sessionn = $sessions->where('id', $all_session['id'])->where('option_slug', 'auto-date');
                // $session_count = $sessionn->count();
                ?>
                <div class="d-flex justify-content-between align-items-center">
                    @if($_count!=0)
                        <h5 class="m-0">{{__('education.Online Training Schedule')}}</h5>
                    @endif
                </div>

                @if($_count!=0)

                <table class="tc-table table table-striped table-responsive bg-light text-center border" id="table-5">

                    <thead class="bg-secondary text-white">
                    <tr style="background: #fb4400">
                        @if($coin_id==334)
                            <th colspan="11" style="border-left: 2px solid #dee2e6;">{{__('education.SAR_currency')}}</th>
                        @else
                            <th colspan="11" style="border-left: 2px solid #dee2e6;">{{__('education.USD')}}</th>
                        @endif
                    </tr>
                    <tr>
                        <th rowspan="2">{{__('education.Date')}}</th>
                        <th rowspan="2">{{__('education.Duration')}}</th>
                        <th rowspan="2">{{__('education.Time')}}</th>
                        <th rowspan="2">{{__('education.language')}}</th>
                        <th style="border-left: 2px solid #dee2e6;">{{__('admin.Course Price')}}</th>
                        <th>{{__('admin.discount')}}</th>
                        <th>{{__('admin.Exam Price')}}</th>
                        <th>{{__('admin.take2_price')}}</th>
                        <th>{{__('education.Sub Total')}}</th>
                        <th>{{__('admin.VAT')}}</th>
                        <th>{{__('admin.Total after VAT')}}</th>
                        {{-- <th rowspan="2" style="border-left: 2px solid #dee2e6;">{{__('education.Registration')}}</th> --}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sessions->where('id', $all_session['id'])->where('option_slug', 'auto-date') as $session)

                        @if(!is_null($session->session_id))

                        {{$SessionHelper->SetCourse($session)}}
                        <tr data-id="{{$session->session_id}}">
                            <td>
                                <span>{{App\Helpers\Date::IsoFormat($session->session_date_from)}}</span> -
                                <span>{{App\Helpers\Date::IsoFormat($session->session_date_to)}}</span>

                                @include('training.training-schedule.registerBtn', compact('session'))
                            </td>
                            <td>{{$SessionHelper->SessionDuration()}}</td>
                            <td>
                                {!! $session->session_time !!}
                                @if ($session->except_fri_sat == 1)
                                    {{__('admin.except_fri_sat')}}
                                @endif
                            </td>
                            <td>{{App\Helpers\Lang::TransTitle($session->language_name)}}</td>

                            <?php
                                $Discount = $SessionHelper->Discount();
                                $discount_value = $SessionHelper->DiscountValue();
                                $vat_value = $SessionHelper->VATFortPriceWithExamPrice();
                                $sub_total = $SessionHelper->PriceWithExamPrice();
                            ?>

                            <td>{{NumberFormatWithComma($session->session_price)}}</td>
                            @if(empty($Discount))
                                <td></td>
                            @else
                                <td>{{$session->discount_value}} %<br>-{{NumberFormatWithComma($discount_value)}}</td>
                            @endif
                            <td>
                                {{NumberFormatWithComma($session->session_exam_price)}}
                                @if($SessionHelper->ExamIsIncluded()==1)
                                    <br>
                                    <small class="fas fa-plus"></small>
                                    <small>{{__('education.Exam Included')}}</small>
                                @endif
                            </td>
                            <td>{{NumberFormatWithComma($session->take2_price)}}
                                @if($session->take2_price>0)
                                    <br>
                                    <small>Not Included</small>
                                @endif
                            </td>
                            <td>{{NumberFormatWithComma($sub_total)}}</td>
                            <td>{{NumberFormatWithComma($vat_value)}}</td>

                            <td>{{NumberFormatWithComma($SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT())}}
                            <?php

                            $main_price_before_discount_with_vat = $SessionHelper->PriceWithExamPriceAfterVAT();
                            ?>
                            @if ($Discount > 0)
                            <br>
                                @if($main_price_before_discount_with_vat!=0)
                                    <small class="mx-1 text-secondary" style="text-decoration:line-through;">
                                        {{NumberFormatWithComma($main_price_before_discount_with_vat)}}
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
                            </td>
                            {{-- <td>
                                <a class="btn btn-primary mb-1" name="session_id" href="{{route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id, 'coin_id'=>$coin_id])}}">{{__('education.Register')}}</a>
                            </td> --}}
                        </tr>

                        @endif
                    @endforeach
                    </tbody>
                </table>
                @endif
                {{-- ===================  End of Online Training Schedule ======================== --}}

            </div>
        </div> <!-- #trainig-schedule -->

    </div>
</div>
@endforeach
