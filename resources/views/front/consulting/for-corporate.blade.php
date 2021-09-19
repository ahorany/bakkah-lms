@extends(FRONT.'.consulting.layouts.master')

@include('SEO.infa', ['infa_id'=>87])

@section('content')

    @include(FRONT.'.consulting.Html.page-header', ['title'=>__('consulting.Talk to an Expert')])

    <?php $path = FRONT.'.consulting.consulting-parts'; ?>

    <div class="container mt-5">

        <div class="row">
            <div class="col-12">
                <img class="w-100" src="{{CustomAsset('front-dist/images/For-Corporate.jpg')}}" alt="Training Programs For Organizations" title="Training Programs For Organizations">
            </div>
        </div>

        <div class="row justify-content-center mt-5 py-5">
            <div class="col-md-6">
                {!! __( 'consulting.For Corporate Details' ) !!}
                @include($path.'.USP')
            </div>
            <div class="col-md-6">
                @if(app()->isLocale('en'))
                    <div class="pipedriveWebForms" data-pd-webforms="https://webforms.pipedrive.com/f/2Z744h03Lf6PaBzEKHU1dYqvx153WzdDPDaKdDTmuBTxh8dbrhoFeU4VfqKRC9aKL"><script src="https://webforms.pipedrive.com/f/loader"></script></div>
                @else
                    <div class="pipedriveWebForms" data-pd-webforms="https://webforms.pipedrive.com/f/32O4T7GxlQtmwww6qvqYgmt8sMyf25SUi0UhnFX9ApA0TZJFMDDGzSNPNql1zOfPt"><script src="https://webforms.pipedrive.com/f/loader"></script></div>
                @endif
                <!--<div class="pipedriveWebForms" data-pd-webforms="https://webforms.pipedrive.com/f/32O4T7GxlQtmwww6qvqYgmt8sMyf25SUi0UhnFX9ApA0TZJFMDDGzSNPNql1zOfPt"><script src="https://webforms.pipedrive.com/f/loader"></script></div>-->
            </div>
        </div>
    </div>

    @include($path.'.clients')

@endsection
