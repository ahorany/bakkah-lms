<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>{{__('app.app_title')}}</title>

  @include(ADMIN.'.layouts.dist.css')
  <!-- jQuery -->
  <script src="{{CustomAsset(ADMIN.'-dist/jquery/jquery.min.js')}}"></script>

  <script src="{{CustomAsset('js/vue.min.js')}}"></script>
  {{-- <script defer src="{{ CustomAsset('js/app.js') }}"></script> --}}
  <script src="{{CustomAsset('js/axios.min.js')}}"></script>

</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    @include(ADMIN.'.layouts.aside')

    @include(ADMIN.'.layouts.nav')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      @include(ADMIN.'.layouts.header')

      {!!Builder::Content()!!}
        @yield('content')
      {!!Builder::EndContent()!!}
    </div>

    @include(ADMIN.'.layouts.footer')
  </div>
  <!-- ./wrapper -->

  @include(ADMIN.'.layouts.dist.js')
</body>
</html>
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<script>
    $('.sidebar-open').on('click', function(e) {
        e.preventDefault();
        $('body').addClass('sidebar-open');
    })
    $('.sidebar-close').on('click', function(e) {
        e.preventDefault();
        $('body').removeClass('sidebar-open');
    })

    // In your Javascript (external .js resource or <script> tag)
    // $(document).ready(function() {
    //     $('.js-example-basic-single').select2();
    // });
</script>

