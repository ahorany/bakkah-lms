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
    <link rel="stylesheet" href="{{CustomAsset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{CustomAsset('assets/css/custom-style.css')}}">

    @yield('style')
</head>

<body>
<?php  $user_role_name = auth()->user()->roles()->select('roles.name')->first()->trans_name??null; ?>

@include("layouts.header")

<div class="container-fluid">
    <div class="row align-items-center justify-content-center">

        <main class="col-md-10 col-lg-8 col-xl-6 px-4 py-5">

            <div class="container congrats">
                <div class="text-center course-image certificate certification-card">
                    <a href="#" class="download">
                        <img src="{{CustomAsset('icons/download.svg')}}" width="50px" alt="">
                    </a>
                    <div class="no-img certificate-img" style="display:flex; align-items: center; justify-content: center;">
                        <img src="{{CustomAsset('icons/certificate.svg')}}" height="auto" width="30%">
                    </div>
                    <div>
                        <h1>Congratulations!</h1>
                        <p>
                            You have successfully completed the course. Can’t wait for to hear the good news about you getting certified! <br><br>
                            Good Luck in your exam
                        </p>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>


<script src="{{CustomAsset('assets/js/main.js')}}"></script>

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

