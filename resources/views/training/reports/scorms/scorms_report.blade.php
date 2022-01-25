@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.scorm_Reports')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')

    <div class="scorm_report">
        <div  class="course_info mb-3 card p-3">
            <div class="row mx-0">
                <div class="col-md-6">
                    <a href="{{route('training.scormsReportOverview')}}" class="group_buttons btn-sm overview">Overview</a>
                    <a  href="{{route('training.scormsReportScorms')}}" class="group_buttons btn-sm scorms">Scorms</a>
                </div>
            </div>
        </div>

        @if(isset($overview))
            @include('training.reports.scorms.dashboard')
        @endif

        @if(isset($scorms))
            @include('training.reports.scorms.scorms')
        @endif
    </div>

@endsection

