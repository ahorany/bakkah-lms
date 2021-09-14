@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(84)??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>__('consulting.About Bakkah Inc')])

    @include(FRONT.'.consulting.about-us.section', ['index'=>0, 'number'=>'01'])

    @include(FRONT.'.consulting.about-us.mission-vission')

    @include(FRONT.'.consulting.about-us.section2')

    @include(FRONT.'.consulting.about-us.statistics')

{{--    @include(FRONT.'.consulting.about-us.testimonials')--}}

    @include(FRONT.'.consulting.about-us.section', ['index'=>2, 'number'=>'05'])

@endsection
