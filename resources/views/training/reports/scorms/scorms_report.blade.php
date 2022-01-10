@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.scorm_Reports')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')

    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-6">
                {{-- <span class="mr-1 p-1 badge badge-dark" style="font-size: 0.8rem;">{{$course->trans_title}}</span> --}}
                <a href="{{route('training.scormsReportOverview')}}" class="group_buttons btn-sm overview">Overview</a>
                <a  href="{{route('training.scormsReportScorms')}}" class="group_buttons btn-sm scorms">Scorms</a>
                {{--<a href="{{route('training.coursesReportTest',['id'=>$course_id])}}" class="group_buttons btn-sm">Tests</a> --}}
                {{-- <a href="{{route('training.course_users',['id'=>$course_id])}}" class="group_buttons btn-sm">Certificates</a> --}}
            </div>
        </div>
    </div>

    @if(isset($overview))
        @include('training.reports.scorms.dashboard')
    @endif

    @if(isset($scorms))
        @include('training.reports.scorms.scorms')
    @endif

    {{-- @if(isset($tests))
        @include('training.reports.scorms.tests')
    @endif --}}

@endsection

