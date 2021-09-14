<!DOCTYPE html>

<html lang="{{app()->getLocale()}}" dir="{{LaravelLocalization::getCurrentLocaleDirection()}}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('useHead')

    @yield('d_title')

    {{--  @include(FRONT.'.education.layouts.header-meta')  --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"> --}}
    <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/css/all.css')}}">
    {{-- <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/all.css')}}"> --}}

    @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
        <link rel="stylesheet" href="{{CustomAsset(FRONT.'-dist/rtl.min.css')}}">
    @endif

    <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/jquery.min.js')}}"></script>
    {{-- <script type='text/javascript' src="{{CustomAsset(FRONT.'-dist/js/script.js')}}"></script> --}}
    <link rel="icon" href="{{CustomAsset('images/logo_50.png')}}" sizes="32x32" />

    {{-- <script src="{{CustomAsset('js/vue.min.js')}}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.min.js"></script>

    @include(FRONT.'.social_scripts.header')

    <?php
    if(isset($navbar_campaign)) {
    $end_date = $navbar_campaign->date_to;
    $newDate = date("M d, Y", strtotime($end_date));
    ?>

    <script>
        var end_date = '<?php echo $newDate; ?>';
        var countDownDate = new Date(end_date + " 00:00:00").getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            var days_en = " Day, ";
            if(days > 1) {
            days_en = " Days, ";
            }
            @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
                document.getElementById("timecountdown").innerHTML = days + " يوم, " + hours + " ساعة, "
                + minutes + " دقائق, " + seconds + " ثواني متبقية";
            @else
                document.getElementById("timecountdown").innerHTML = days + days_en + hours + " Hours, "
                + minutes + " Minutes, " + seconds + " Seconds Left";
            @endif
            document.getElementById("space").innerHTML = "-";
            if (distance < 0) {
                clearInterval(x);
                @if(LaravelLocalization::getCurrentLocaleDirection()=='rtl')
                    document.getElementById("timecountdown").innerHTML = "إنتهى";
                @else
                    document.getElementById("timecountdown").innerHTML = "EXPIRED";
                @endif
            }
        }, 1000);
    </script>

    <?php } ?>

    @yield('style')

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
         @include('cookieConsent::index', ['route'=>CustomRoute('education.static.static-page', ['post_type'=>'cookies-policy'])])
    </main>

    @include(FRONT.'.education.layouts.cart-modal')
</div>
    @include(FRONT.'.education.layouts.footer-list')
    @include(FRONT.'.education.layouts.footer')
    <script src="{{CustomAsset('js/axios.min.js')}}"></script>
    @include('cookieConsent::index', ['route'=>CustomRoute('education.static.static-page', ['post_type'=>'cookies-policy'])])
    @yield('scripts')
</body>
</html>
