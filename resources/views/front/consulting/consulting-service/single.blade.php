@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$postForTitle??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.consulting-service.page-header')

    <!-- |||||||||||||||||||||||||| MAIN CONTENT OF PM SERVICES |||||||||||||||||||||||||||||| -->
    <div class="main-content py-5">
        <div class="container container-padding">
            <div class="row">
                <div class="col-sm-8">

                    <div class="consulting-content">
                        <h2 class="fancy-title">{{$postForTitle->title}}</h2>
                        {!! $postForTitle->details !!}

                        @include(FRONT.'.Html.share')

                    </div>

                </div>
                <div class="col-sm-4 sidebar related_courses">

                    @include(FRONT.'.consulting.consulting-service.sections')

                </div>
            </div>
        </div>
        <!-- <div class="extra_space"></div> -->
    </div>
    <!-- ||||| MAIN CONTENT OF PM SERVICES ||||| -->
@endsection
