@foreach($all_sessions as $all_session)
<?php $_count = $sessions->where('id', $all_session['id'])->where('option_slug', 'auto-date')->count(); ?>
<div class="col-12">
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h4 class="m-0"><a href="{{route('education.courses.single', ['slug'=>$all_session['slug']])}}" class="course_name">{{App\Helpers\Lang::TransTitle($all_session['title'])}}</a></h4>
        </div>

        <div class="bg-light py-5" id="trainig-schedule">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    @if($_count!=0)
                        <h2 class="m-0">{{__('education.Online Training Schedule')}}</h2>
                    @endif

                    @foreach($sessions->where('id', $all_session['id'])
                    ->where('option_slug', 'custom-date') as $session)
                        <a href="{{route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id])}}" class="btn btn-primary">{{__('education.Register Now For')}} {{__('education.'.$session->option__post_type)}}</a>
                    @endforeach

                </div>

                @if($_count!=0)
                <table class="tc-table table table-striped table-responsive bg-light text-center card border" id="table-5">
                    <thead class="bg-primary text-white">
                    <tr>
                        <th>{{__('education.Date')}}</th>
                        <th>{{__('education.Duration')}}</th>
                        <th>{{__('education.Time')}}</th>
                        <th>{{__('education.language')}}</th>
                        <th>{{__('education.Price')}}</th>
                        <th>{{__('education.Registration')}}</th>
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
                            </td>
                            <td>{{$SessionHelper->SessionDuration()}}</td>
                            <td class="td-time">
                                {!! $session->session_time !!}
                                @if ($session->except_fri_sat == 1)
                                    {{__('admin.except_fri_sat')}}
                                @endif
                            </td>
                            <td>{{App\Helpers\Lang::TransTitle($session->language_name)}}</td>
                            <td>{{NumberFormatWithComma($SessionHelper->PriceAfterDiscountWithExamPriceAfterVAT())}} {{__('education.SAR')}}
                            <?php
                            $Discount = $SessionHelper->Discount();
                            $main_price_before_discount_with_vat = $SessionHelper->PriceWithExamPriceAfterVAT();
                            ?>
                            @if ($Discount > 0)
                            <br>
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
                            </td>
                            <td>
                                @guest
                                    <a class="btn btn-secondary" name="session_id" href="{{route('education.courses.register', ['slug'=>$session->slug, 'session_id'=>$session->session_id])}}">{{__('education.Register')}}</a>
                                @endguest
                                <button class="btn btn-primary" @click="addCartItem('{{$session->session_id}}', 'training_option')" :disabled="chackIfExistCart('{{$session->session_id}}')">{{__('education.Add to Cart')}}</button>
                            </td>
                        </tr>

                        @endif
                    @endforeach
                    </tbody>
                </table>
                @endif

            </div>
        </div> <!-- #trainig-schedule -->

    </div>
</div>
@endforeach
