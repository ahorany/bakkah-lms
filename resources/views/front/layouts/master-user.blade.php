<!DOCTYPE html>

<html lang="{{app()->getLocale()}}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('education.Dashboard')) | {{__('education.DC_title')}}</title>

    {{--    @include(FRONT.'.education.layouts.header-meta')--}}
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/css/all.css')}}">
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/all.css')}}">

    @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
        <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/rtl.min.css')}}">
    @endif

    <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/jquery.min.js')}}"></script>
    <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/script.js')}}"></script>
    <link rel="icon" href="{{CustomAsset('images/logo_50.png')}}" sizes="32x32" />

    @include(FRONT.'.social_scripts.header')
</head>

<body class="home" data-spy="scroll" data-target="#course-deatilas-tabs" data-offset="200">

<div class="page-content-wrapper">

    <header class="education-header sticky-top header-shadow">

        @include(FRONT.'.education.layouts.navbar-user')

    </header>


    <main>
        @yield('content')
    </main>

    @include(FRONT.'.education.layouts.footer')
