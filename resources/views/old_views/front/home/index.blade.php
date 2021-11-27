@extends(FRONT.'.home.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(69)??null])
@endsection

@section('content')

    <div class="main-content">
        <div class="images-bg">
            <img src="{{CustomAsset(FRONT.'-dist/images/img1.jpg')}}" alt="">
        </div> <!-- /.images-bg -->

        <img src="{{CustomAsset(FRONT.'-dist/images/logo.png')}}" class="logo" alt="{{__('front.home.DC_title')}}">

        <div class="div-home-lang">
            @include(FRONT.'.Html.switch-lang')
        </div>

        <div class="main-services">
            <div class="service-box consulting">
                <a class="text-white" href="{{CustomRoute('consulting.index')}}">
                    <div class="title">
                        <h2>{{__('home.consulting')}}</h2>
                        <span>{{__('home.consulting-company')}}</span>
                    </div>
                    <div class="content">
                        {{__('home.consulting-content')}}
                    </div>
                </a>
            </div> <!-- /.service-box.consulting -->

            <div class="service-box education">
                <a class="text-white" href="{{CustomRoute('education.index')}}">
                    <div class="title">
                        <h2>{{__('home.education')}}</h2>
                        <span>{{__('home.education-company')}}</span>
                    </div>
                    <div class="content">
                        {{__('home.education-content')}}
                    </div>
                </a>
            </div> <!-- /.service-box.consulting -->
        </div>

        @include(FRONT.'.home.layouts.home-copyright')

    </div> <!-- /.home-content -->

@endsection
