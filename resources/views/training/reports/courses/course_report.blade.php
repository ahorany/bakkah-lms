@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Course Reports')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')

    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-6">

                <a href="{{route('training.coursesReportOverview',['id'=>$course_id])}}" class="group_buttons btn-sm">Overview</a>
                <a  href="{{route('training.coursesReportUser',['id'=>$course_id])}}" class="group_buttons btn-sm">Users</a>
                <a href="{{route('training.coursesReportTest',['id'=>$course_id])}}" class="group_buttons btn-sm">Tests</a>
                {{-- <a href="{{route('training.course_users',['id'=>$course_id])}}" class="group_buttons btn-sm">Certificates</a> --}}

            </div>
        </div>
    </div>

    @if(isset($overview))
        @include('training.reports.courses.dashboard')
    @endif

    @if(isset($users))
        @include('training.reports.courses.users')
    @endif

    @if(isset($tests))
        @include('training.reports.courses.tests')
    @endif

@endsection
