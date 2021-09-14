@extends(FRONT.'.education.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(72)??null])
@endsection

@section('content')

    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.Contact Us')])
    <div class="main-content py-5">
        <div class="container container-padding">

            @include('front.education.contacts.contact_info')

            {{-- @include('front.education.Html.errors') --}}
            @include('front.education.Html.alert')

            @include('front.education.contacts.form')

            @include('front.education.contacts.map')

            @include('front.education.contacts.social')

        </div>

    </div>
@endsection
