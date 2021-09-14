<div class="course-info-box">
    @section('style')
    <?php
    if($method == 'self-study') {
        ?>
        <style>
            .faq.self-study {
                display: block;
            }
            .faq.online-training {
                display: none;
            }

            @media (max-width: 767px) {
                .course-info-box .course-info-box-mobile.mobile-self-price {
                    display: block;
                }

                .course-info-box .course-info-box-mobile.mobile-online-price {
                    display: none;
                }
            }
        </style>
        <?php
    }else { ?>
        <style>
            .faq.self-training {
                display: block;
            }
            .faq.online-study {
                display: none;
            }

            @media (max-width: 767px) {
                .course-info-box .course-info-box-mobile.mobile-online-price {
                    display: block;
                }

                .course-info-box .course-info-box-mobile.mobile-self-price {
                    display: none;
                }
            }

        </style>
    <?php } ?>
    @endsection

    {{-- @dd(count($CardsSingles)) --}}

    {{-- @if(Carbon\Carbon::parse($course->created_at)->addDays(30) >= Carbon\Carbon::now())
    <div class="badge bg-info text-white">{{__("education.New")}}</div>
@endif --}}
    @if (isset($CardsSingles) && !empty($CardsSingles))
        {{-- @dump(itil_exam_simulators) --}}
        <ul class="list-unstyled course-info__buttons">
            <?php
                $active = '';
                $card_count = 0;
                $option__post_type = null;
            ?>
            @foreach($CardsSingles as $CardsSingle)
                {{--@if($CardsSingle->slug == 'cbap')--}}
                <?php
                if($method==$CardsSingle->option__post_type || $CardsSingle->option__post_type=='exam-simulator' || (count($CardsSingles) == 1 && $CardsSingle->option__post_type=='self-study')){
                    $active = 'active';
                }
                ?>
                <li>
                    <a class="{{$active == 'active' ? 'active' : ''}}" href="#{{($CardsSingle->constant_id == 13) ? 'online-training-card' : 'self-study-card'}}">
                    {{-- <a class="{{$CardsSingle->option__post_type == $method || $CardsSingle->option__post_type == 'exam-simulator' ? 'active' : ''}}" href="#{{($CardsSingle->constant_id == 13) ? 'online-training-card' : 'self-study-card'}}"> --}}
                    {{App\Helpers\Lang::TransTitle($CardsSingle->constant_name)}}

                    @if ($CardsSingle->option__post_type=='self-study' )
                        <small style="position: relative;top: -2px;font-size: 10px;" class="px-2 bg-info text-white">{{__("education.New")}}</small>
                    @endif

                    </a>
                </li>
                <?php
                $option__post_type = $CardsSingle->option__post_type;
                // if($CardsSingle->constant_id==353){
                //     $option__post_type = 'exam-simulator-training';
                // }
                $active = '';
                $card_count++;
                ?>
            @endforeach

            <?php
            $partner_id = isset($course->partner_id)?$course->partner_id:-1;
            ?>
            @if($partner_id != 12)
                @if($card_count == 1 && $option__post_type=='online-training' && $course->id!=1) {{-- PMP --}}
                <li class="soon">
                    <a href="#soon-card">{{__('education.Self Study')}} <small>{{__('education.soon')}}</small></a>
                </li>
                @endif
            @endif
        </ul>
    @else
    <div class="d-block">
        <a href="{{route('education.courses.interest', ['slug'=>$course->slug])}}" class="btn btn-primary btn-block mb-4">{{__('education.Are you interested ?')}}</a>

        <?php $trainingOption = $course->trainingOption()->where('constant_id', '13')->first(); ?>
        {!! $trainingOption->trans_details !!}

        <hr>
        <div class="card-text">
            <?php
            $detail = App\Models\Admin\Detail::where('constant_id', 314)
            ->where('detailable_type', 'App\Models\Training\TrainingOption')
            ->first();
            ?>
            <h4>{{$detail->constant->trans_name??null}}</h4>
            {!! $detail->trans_details??null !!}
            <p class="text-center mt-4 mb-0"><a href="{{route('education.for-corporate')}}" class="btn btn-outline-secondary">{{ __('education.Register Group') }}</a></p>
        </div>

    </div>
    @endif

    <?php
    $active = '';
    $card_count = 0;
    $option__post_type = null;
    ?>
    @foreach($CardsSingles as $CardsSingle)
        @if(!is_null($CardsSingle->session_id))
            <?php
            if($method==$CardsSingle->option__post_type || $CardsSingle->option__post_type=='exam-simulator' || (count($CardsSingles) == 1 && $CardsSingle->option__post_type=='self-study')){
                $active = 'active';
            }
            ?>
            @include(FRONT.'.education.products.single.main-card.card', ['CardsSingle'=>$CardsSingle, 'active'=>$active])
            <?php $active = ''; ?>
        @endif
    @endforeach
    {{-- @foreach($course->trainingOptions as $trainingOption)
        @includeWhen(isset($trainingOption->session), FRONT.'.education.courses.single.info.options.online', [
            'trainingOption'=>$trainingOption,
        ])
    @endforeach --}}
    {{-- @if($check!=0)

        @foreach($course->trainingOptions as $trainingOption)
            @includeWhen(isset($trainingOption->session), FRONT.'.education.courses.single.info.options.online', [
                'trainingOption'=>$trainingOption,
            ])
        @endforeach

        @foreach($selfCourse->trainingOptions as $trainingOption)
            @includeWhen(isset($trainingOption->session), FRONT.'.education.courses.single.info.options.self', [
                'trainingOption'=>$trainingOption,
            ])
        @endforeach

    @else
        @foreach($course->trainingOptions as $trainingOption)
            <div class="d-block">
                <a href="{{route('education.courses.interest', ['slug'=>$course->slug])}}" class="btn btn-primary btn-block mb-4">{{__('education.Are you interested ?')}}</a>

                {!! $trainingOption->trans_details !!}
                @include(FRONT.'.education.courses.single.info.more-people')
            </div>
        @endforeach
    @endif --}}
</div>
