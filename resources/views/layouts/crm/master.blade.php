<!doctype html>
<html @if(!env('APP_DEBUG'))  oncontextmenu="return false;"@endif lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bakkah LMS">
    <meta name="author" content="Bakkah LMS">
    <meta name="generator" content="Bakkah LMS">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('useHead')

    <link rel="icon" href="{{CustomAsset('assets/images/logo.png')}}">

    <!-- Bootstrap CSS -->
    {{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="{{CustomAsset('assets/css/bootstrap-grid.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <link rel="stylesheet" href="https://bakkah.com/public/admin-dist/select2/select2-bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{CustomAsset('assets/css/style.css')}}?v={{time()}}">
    <link rel="stylesheet" href="{{CustomAsset('assets/css/custom-style.css')}}?v={{time()}}">

    <link rel="stylesheet" href="{{CustomAsset('css/responsive.css')}}?v={{time()}}">

    @yield('style')
<style>
        .person-wrapper h2{
            margin-top: 20px !important;
            font-weight: 600 !important;
            margin-bottom: 5px !important;
        }

        span.select2 {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {

            top: 7px !important;
        }

        .select2-container .select2-selection--single {
            height: calc(2.25rem + 2px) !important;
        }

</style>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>

<div class="container-max">
    @include("layouts.header")

    <div class="container-fluid">
        <div class="row">

            @include("layouts.sidebar")

            <main class="col-md-9 ms-sm-auto col-lg-9 col-xl-10 p-5" id="main-vue-element">
                @yield('content')
            </main>
        </div>
    </div>
</div>


<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{CustomAsset('assets/js/main.js')}}?v={{time()}}"></script>
@include('layouts.crm.dist.js')
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

    $(document).ready(function(){
        $('[type=reset]').click(function(){
            $('.input_search').attr('value','');
        });
    });


    $('button[name="clear_search"]').click(function(){
            $('input[type="text"]').val('');
            $('select').val(-1);

        });

</script>

@yield('script')

</body>

</html>
