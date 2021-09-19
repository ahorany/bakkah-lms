@extends(FRONT.'.education.layouts.master')

@include('SEO.infa', ['infa_id'=>90])

@section('content')

    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.Hot Deals')])

    <section class="pb-5 sessions-category">

        <div class="container">

            @include(FRONT.'.education.courses.card', ['courses'=>$courses, 'page' => 'hot-deals'])

        </div>
    </section>
@endsection
