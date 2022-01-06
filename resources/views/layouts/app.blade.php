<!doctype html>
<html @if(!env('APP_DEBUG'))  oncontextmenu="return false;"@endif lang="en">

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
    <link rel="stylesheet" href="{{CustomAsset('assets/css/style.css')}}?v={{time()}}">
    <link rel="stylesheet" href="{{CustomAsset('assets/css/custom-style.css')}}?v={{time()}}">

    <link rel="stylesheet" href="{{CustomAsset('css/responsive.css')}}?v={{time()}}">

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('style')
</head>

<body>
<?php  $user_role_name = auth()->user()->roles()->select('roles.name')->first()->trans_name??null; ?>
<div class="container-max">

    @include("layouts.header")

    <div class="container-fluid"><!--fluid-->
        <div class="row">

            @if(Route::current()->getName() != 'user.congrats')
                @include("layouts.sidebar")
            @endif

            <main class="col-md-9 ms-sm-auto col-lg-9 col-xl-10 p-5" id="main-vue-element">
                @yield('content')
            </main>
        </div>
    </div>
</div>


<script src="{{CustomAsset('assets/js/main.js')}}?v={{time()}}"></script>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
<script>
    document.querySelector('html,body').onclick = function () {
       document.querySelectorAll('.has-dropdown .dropdown').forEach((el) => {
           if(!el.classList.contains('d-none')){
                el.classList.add('d-none')
           }
       })
    }
</script>
@yield('script')

</body>

</html>
