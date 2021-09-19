@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(83)??null])
@endsection

@section('content')

    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.About Bakkah Inc')])

    @include(FRONT.'.education.about-us.section', ['index'=>0, 'number'=>'01'])

    @include(FRONT.'.education.about-us.mission-vission')

    @include(FRONT.'.education.about-us.section2')

    @include(FRONT.'.education.about-us.statistics')

    @include(FRONT.'.education.about-us.testimonials')

    @include(FRONT.'.education.about-us.section', ['index'=>2, 'number'=>'05'])

@endsection
