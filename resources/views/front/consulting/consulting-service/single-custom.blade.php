@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.consulting-service.page-header')

    <!-- |||||||||||||||||||||||||| MAIN CONTENT OF PM SERVICES |||||||||||||||||||||||||||||| -->
    <div class="main-content py-5">
        <div class="container mb-5 wow fadeInUp">
            <div class="section-title text-center">
                <h2>{!! __('consulting.do_you_know') !!}</h2>
            </div>

            <div class="row mb-5 big-progress">
                <?php
                $count = $post->services->count();
                $cls = [
                    2=>'col-lg-6',
                    7=>'col-lg-4 small-progress',
                    11=> ($count == 9) ? 'col-md-6 col-lg-3 thin-progress' : 'col-md-4 small-progress',
                ];

                ?>
                @foreach($post->services as $service)
                    <?php
                    $iteration = 2;
                    $borderClass = 'border-dark';
                    if($loop->iteration<=2) {
                        $iteration = 2;
                        $borderClass = 'border-info';
                    }
                    else if($loop->iteration<=5)
                        $iteration = 7;
                    else if($loop->iteration<=11)
                        $iteration = 11;
                    ?>
                    <div class="{{$cls[$iteration]}} mb-4">
                        <div class="d-flex consulting-progress  shadow p-4">
                            <div class="progress" data-value="{{$service->percentage}}">
                                <span class="progress-left">
                                    <span class="progress-bar {{$borderClass}}"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar {{$borderClass}}"></span>
                                </span>
                                <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                                <div class="h4 font-weight-bold">{{$service->percentage}}%</div>
                                </div>
                            </div>
                            <div class="mx-3">
                                <h1 class="boldfont">{{$service->trans_title}}</h1>
                                <p class="lead m-0">{!!$service->trans_excerpt!!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="devider pt-3">
            <div class="container">
                <hr>
            </div>
        </div>

        <div class="container consulting-plans pt-5 wow fadeInUp">
            <div class="section-title text-center mb-5">
                {!! __('consulting.Bakkah Consulting helps you to achieve Results') !!}
            </div>
            <div class="row justify-content-center">

                @foreach($post->serviceArchives as $serviceArchive)
                <div class="col-md-4 mb-4">
                    <div class="bg-light main-text check-list p-4 mb-3">
                        <h3 class="text-center boldfont mb-4">{{$serviceArchive->trans_title}}</h3>
                        {!! $serviceArchive->trans_details !!}
                    </div>
                    <div class="second-text">
                        {!! $serviceArchive->trans_excerpt !!}
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        {{-- <div class="container container-padding">
            <div class="row">
                <div class="col-sm-12">

                    <div class="consulting-content">
                        <h2 class="fancy-title">{{$post->title}}</h2>
                        {!! $post->details !!}

                        @include(FRONT.'.Html.share')

                    </div>

                </div>
                <div class="col-sm-4 sidebar related_courses">

                    @include(FRONT.'.consulting.consulting-service.sections')

                </div>
            </div>
        </div> --}}
        <!-- <div class="extra_space"></div> -->
        <?php $path = FRONT.'.consulting.consulting-parts'; ?>
        @include($path.'.clients')

        <section class="py-5 mb-5 wow fadeInUp">
            <div class="container">
                <div class="section-title text-center mb-5">
                    {!! __('consulting.Bakkah Management Consulting Methodology') !!}
                    {!! __('consulting.it contains 4 steps and 10 perspectives') !!}
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <div class="method-box bg-light p-4">
                            <h3 class="text-center mb-3">{!! __('consulting.Building Capabilities') !!}</h3>
                            <ul>
                                <li>{!! __('consulting.Professional Programs') !!}</li>
                                <li>{!! __('consulting.Executive Programs') !!}</li>
                                <li>{!! __('consulting.Customized Programs') !!}</li>
                                <li>{!! __('consulting.Exams & Certifications') !!}</li>
                                <li>{!! __('consulting.Professionals Outsourcing') !!}</li>
                                <li>{!! __('consulting.Executive Headhunting') !!}</li>
                                <li>{!! __('consulting.Board Services') !!}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-5">
                        @if (App::isLocale('en'))
                            <img class="w-100" src="{{ CustomAsset('images/cunsulting.png') }}" alt="">
                        @else
                            <img class="w-100" src="{{ CustomAsset('images/cunsulting_ar.png') }}" alt="">
                        @endif
                    </div>
                    <div class="col">
                        <div class="method-box bg-light p-4">
                            <h3 class="text-center mb-3">{!! __('consulting.Do You Know Sustainable Value Creation') !!}</h3>
                            <ul>
                                <li>{!! __('consulting.Strategy Development') !!}</li>
                                <li>{!! __('consulting.Corporate Governance') !!}</li>
                                <li>{!! __('consulting.Frameworks & Methedologies') !!}</li>
                                <li>{!! __('consulting.Organizational Design') !!}</li>
                                <li>{!! __('consulting.SMO, PMO & HR Setup') !!}</li>
                                <li>{!! __('consulting.Total Reward Management') !!}</li>
                                <li>{!! __('consulting.Dashboards & Reports') !!}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include($path.'.USP')

        @include($path.'.latest-Insights')

    </div>
    <!-- ||||| MAIN CONTENT OF PM SERVICES ||||| -->
@endsection
