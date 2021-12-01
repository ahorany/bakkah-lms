<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('useHead')

    <link rel="icon" href="{{CustomAsset('assets/images/logo.png')}}">
    <link rel="stylesheet" href="{{CustomAsset('assets/css/bootstrap-grid.min.css')}}">
    <link rel="stylesheet" href="{{CustomAsset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{CustomAsset('assets/css/custom-style.css')}}">

    @yield('style')
</head>

<body>
<?php  $user_role_name = auth()->user()->roles()->select('roles.name')->first()->trans_name??null; ?>

@include("layouts.header")

<div class="container-fluid">
    <div class="row">

@include("layouts.sidebar")

        <main class="col-md-9 ms-sm-auto col-lg-9 col-xl-10 p-5" id="main-vue-element">
            @yield('content')
        </main>
    </div>
</div>


<script src="{{CustomAsset('assets/js/main.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
@yield('script')

</body>

</html>
