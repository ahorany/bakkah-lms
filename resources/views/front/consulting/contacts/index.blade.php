@extends(FRONT.'.consulting.layouts.master')

@section('useHead')
    @include('SEO.head', ['eloquent'=>\App\Infastructure::find(73)??null])
@endsection

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>__('education.Contact Us')])
    <div class="main-content py-5">
        <div class="container container-padding">

            @include('front.consulting.contacts.contact_info')

            {{-- @include('front.consulting.Html.errors') --}}
            @include('front.consulting.Html.alert')

            @include('front.consulting.contacts.form')

            @include('front.consulting.contacts.map')

            @include('front.consulting.contacts.social')

        </div>

    </div>
@endsection
