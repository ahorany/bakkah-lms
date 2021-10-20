<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     @if(auth()->check())
        <meta name="userId" content="{{ auth()->id() }}">
    @endif
    @yield('useHead')

    @yield('d_title')

{{--      @include(FRONT.'.education.layouts.header-meta')--}}
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/css/all.css')}}">
     <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/all.css')}}">
<link rel="stylesheet" href="{{CustomAsset('front-dist/css/lms_crm.css')}}">
<link rel="stylesheet" href="{{CustomAsset('front-dist/css/lms.css')}}">

    @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
        <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/rtl.min.css')}}">
    @endif

    <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/jquery.min.js')}}"></script>
     <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/script.js')}}"></script>
    <link rel="icon" href="{{CustomAsset('images/logo_50.png')}}" sizes="32x32" />

     <script src="{{CustomAsset('js/vue.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.min.js"></script>

{{--    @include(FRONT.'.social_scripts.header')--}}


    @yield('style')

</head>

<body  class="home" data-spy="scroll" data-target="#course-deatilas-tabs" data-offset="200">


<div id="page_content" class="page-content-wrapper">

    @include(FRONT.'.education.layouts.header')

    <main>
        @yield('content')
{{--         @include('cookieConsent::index', ['route'=>CustomRoute('education.static.static-page', ['post_type'=>'cookies-policy'])])--}}
    </main>

{{--    @include(FRONT.'.education.layouts.cart-modal')--}}
</div>
{{--    @include(FRONT.'.education.layouts.footer-list')--}}
{{--    @include(FRONT.'.education.layouts.footer')--}}
    <script src="{{CustomAsset('js/axios.min.js')}}"></script>
{{--    @include('cookieConsent::index', ['route'=>CustomRoute('education.static.static-page', ['post_type'=>'cookies-policy'])])--}}
    @yield('scripts')
</body>
</html>
