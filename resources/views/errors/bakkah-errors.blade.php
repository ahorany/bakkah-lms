@extends(FRONT.'.education.layouts.master')
@section('content')
    <div class="main-content py-5 text-center">
        <h1 class="main-color boldfont display-1 mb-3">{{__('education.Opps!')}}</h1>
        <p class="lead">{{__("education.We can't seem to find the page you're looking for.")}}</p>
        <div class="error-img-wrapper">
            <img class="w-100" src="{{CustomAsset('front-dist/images/404.png')}}" alt="">
            <a class="btn btn-lg btn-primary px-5 py-3" href="{{ route('user.home') }}">{{ __('education.Back To Home') }}</a>
        </div>
    </div>
@endsection
