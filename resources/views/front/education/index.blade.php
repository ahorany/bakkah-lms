@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(70)??null])
@endsection

@section('content')

    <h1 class="sr-only">{{__('education.Bakkah Learning')}}</h1>

    <?php $path = FRONT.'.education.education-parts'; ?>

    @include($path.'.slider')

    @include($path.'.partners')

    @include($path.'.most-popular')

    @include($path.'.USP')

    {{--@include($path.'.upcoming-courses')--}}

    @include($path.'.testimonials-section')

    @include($path.'.clients')
@endsection
