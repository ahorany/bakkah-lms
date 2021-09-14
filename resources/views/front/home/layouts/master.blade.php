<!DOCTYPE html>

<html lang="{{app()->getLocale()}}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="LLEityFvnjBjXZVzNAbFi5NB-GJa7jvbSKi_9W2mEgo" />
    @yield('useHead')
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="alternate" hreflang="en" href="https://bakkah.com/" />
    <link rel="alternate" hreflang="ar" href="https://bakkah.com/ar/" />
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/css/all.css')}}">
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/all.css')}}">
    <link rel="icon" href="{{CustomAsset('images/logo_50.png')}}" sizes="32x32" />

    @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
        <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/rtl.min.css')}}">
    @endif

    @include(FRONT.'.social_scripts.header')
</head>

<body class="homepage">

<main>
    {{--@include(FRONT.'.home.layouts.main-menu')--}}
    @yield('content')

    @include('cookieConsent::index', ['route'=>CustomRoute('consulting.static.cookies-policy')])
</main>

<script type='text/javascript' src='{{CustomAsset(FRONT.'-dist/js/jquery.min.js')}}'></script>
<script>
    jQuery('.main-menu button').click(function() {
        jQuery(this).toggleClass('open');
        jQuery('.main-menu').toggleClass('active');
    })
</script>
</body>
</html>
