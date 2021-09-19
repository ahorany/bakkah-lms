@extends(FRONT.'.education.layouts.master')

@include('SEO.infa', ['infa_id'=>75])

{{-- @dd($courses) --}}
@section('content')

    @include(FRONT.'.education.Html.page-header', ['title'=>__('education.Training Schedule')])

    <section class="trainig-schedule py-5"><span style="display: none;">Online</span>
        <div class="container">

            @include(FRONT.'.education.products.training-schedule.search')

            <div id="all_tables" class="row">
                @include(FRONT.'.education.products.training-schedule.course-card')
            </div>

        </div>
    </section>

    @push('scripts')

    <link rel="stylesheet" href="{{CustomAsset(ADMIN.'-dist/css/jquery-ui.min.css')}}">
    <script src="{{CustomAsset(ADMIN.'-dist/js/jquery-ui.min.js')}}"></script>
    <script>
        jQuery(function(){
            jQuery('[data-date="date"]').datepicker({
                dateFormat: 'yy/mm/dd',
                changeMonth:true,
                changeYear:true,
                // autoclose:true
                // language:'ar'
            });
        });
    </script>
    @endpush
@endsection
