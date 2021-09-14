@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>$post??null, 'type' => 'post'])
@endsection

@section('content')

    @include(FRONT.'.consulting.knowledge-center.page-header')

    <!-- |||||||||||||||||||||||||| MAIN CONTENT OF PM SERVICES |||||||||||||||||||||||||||||| -->
    <div class="main-content py-5">
        <div class="container container-padding">
            <div class="row">
                <div class="col-sm-8">

                    <div class="consulting-content">
                        <img width="1000" height="300" src="{{CustomAsset('upload/thumb450/'.$post->upload->file)}}" class="w-100 h-auto" alt="">
                        <h2 class="fancy-title">{{$post->title}}</h2>
                        {!! $post->details !!}

                        @include(FRONT.'.Html.share')

                    </div>

                </div>
                <div class="col-sm-4 sidebar related_courses ">

                    @include(FRONT.'.consulting.knowledge-center.recent-articles-consulting')

                    @include(FRONT.'.consulting.knowledge-center.sections-consulting')

                </div>
            </div>
        </div>
        <!-- <div class="extra_space"></div> -->
    </div>
    <!-- ||||| MAIN CONTENT OF PM SERVICES ||||| -->
@endsection
