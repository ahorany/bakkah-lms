<!DOCTYPE html>

<html lang="{{app()->getLocale()}}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('useHead')

    {{--  @include(FRONT.'.education.layouts.header-meta')  --}}
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/css/all.css')}}">
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/all.css')}}">

    @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
        <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/rtl.min.css')}}">
    @endif

    <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/jquery.min.js')}}"></script>
    <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/script.js')}}"></script>
    <link rel="icon" href="{{CustomAsset('images/logo_50.png')}}" sizes="32x32" />

    {{-- <script src="{{CustomAsset('js/vue.min.js')}}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="{{CustomAsset('js/axios.min.js')}}"></script>

    @include(FRONT.'.social_scripts.header')
</head>

<body class="home" data-spy="scroll" data-target="#course-deatilas-tabs" data-offset="200">

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T8JKCLQ"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

<div class="page-content-wrapper" id="page_content">

    @include(FRONT.'.education.layouts.header')

    <main>
        @yield('content')

        {{-- @include('cookieConsent::index', ['route'=>CustomRoute('education.static.static-page', ['post_type'=>'cookies-policy'])]) --}}
    </main>

    @include(FRONT.'.education.layouts.footer-list')
    @include(FRONT.'.education.layouts.footer')

    @yield('scripts')
