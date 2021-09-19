<!DOCTYPE html>

<html lang="{{app()->getLocale()}}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('useHead')

    {{--  @include(FRONT.'.consulting.layouts.header-meta')  --}}

    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/consulting/css/all.css')}}">
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/all.css')}}">

    @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
        <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/rtl.min.css')}}">
    @endif

    <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/jquery.min.js')}}"></script>
    <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/script.js')}}"></script>
    <link rel="icon" href="{{CustomAsset('images/logo_50.png')}}" sizes="32x32" />

    @include(FRONT.'.social_scripts.header')

</head>

<body class="home consulting">

<div class="page-content-wrapper">

    @include(FRONT.'.consulting.layouts.header')
    <main>
        @yield('content')

        @include('cookieConsent::index', ['route'=>route('consulting.static.cookies-policy')])
    </main>

</div> <!-- /.page-content-wrapper -->

@include(FRONT.'.consulting.layouts.footer-list')
@include(FRONT.'.consulting.layouts.footer')

@yield('scripts')
