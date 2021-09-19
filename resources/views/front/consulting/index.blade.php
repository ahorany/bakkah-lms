@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(71)??null])
@endsection

@section('content')

    <?php $path = FRONT.'.consulting.consulting-parts'; ?>

    @include($path.'.slider')

    @include($path.'.USP')

    @include($path.'.clients')

    @include($path.'.servies')

    @include($path.'.latest-Insights')

{{--    @include($path.'.newsletter')--}}

@endsection
