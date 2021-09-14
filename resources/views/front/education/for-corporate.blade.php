@extends(FRONT.'.education.layouts.master')

@include('SEO.infa', ['infa_id'=>86])

@section('content')

    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.For Corporate')])

    <?php $path = FRONT.'.education.education-parts'; ?>

    <div class="container mt-5">

        <div class="row">
            <div class="col-12">
                <img class="w-100" src="{{CustomAsset('front-dist/images/For-Corporate.jpg')}}" alt="{{__('admin.Training Programs For Organizations')}}" title="{{__('admin.Training Programs For Organizations')}}">
            </div>
        </div>

        <div class="row justify-content-center mt-5 pt-5">
            <div class="col-md-6">
                {!! __( 'education.For Corporate Details' ) !!}
                @include($path.'.USP')
            </div>
            <div class="col-md-6">
                @if(app()->isLocale('en'))
                    <div class="pipedriveWebForms" data-pd-webforms="https://webforms.pipedrive.com/f/1HIteHSnT3LzxcFI1FlnWNOkBLXHCTnwHCDOzwLOxYLHIzrVc5PaFcYYaPyXU4pJF"><script src="https://webforms.pipedrive.com/f/loader"></script></div>
                @else
                    <div class="pipedriveWebForms" data-pd-webforms="https://webforms.pipedrive.com/f/1Ah8RKURsi3vuhGcHNEfMYy2hwAYnk9YbpzWm7GEW1fFVdUNMnalGZydInlqDJ7V1"><script src="https://webforms.pipedrive.com/f/loader"></script></div>
                @endif
                <!--<div class="pipedriveWebForms" data-pd-webforms="https://pipedrivewebforms.com/form/09269110a8529f7f9c8d5059b51a9b4b6254486"><script src="https://cdn.us-east-1.pipedriveassets.com/web-form-assets/webforms.min.js"></script></div>-->
            </div>
        </div>
    </div>

    @include($path.'.partners')

@endsection
