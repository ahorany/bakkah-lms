@extends('layouts.crm.index')

@section('table')

    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-6">
                <span class="mr-1 p-1 badge badge-dark" style="font-size: 0.8rem;">{{$user->trans_name}}</span>
                <a href="{{route('training.usersReportOverview',['id'=>$user_id])}}" class="group_buttons btn-sm">Overview</a>
                <a  href="{{route('training.usersReportCourse',['id'=>$user_id])}}" class="group_buttons btn-sm">Courses</a>
                <a href="{{route('training.usersReportTest',['id'=>$user_id])}}" class="group_buttons btn-sm">Tests</a>
                {{-- <a href="{{route('training.course_users',['id'=>$user_id])}}" class="group_buttons btn-sm">Certificates</a> --}}
            </div>
        </div>
    </div>
    @if(isset($overview))
        @include('training.reports.users.dashboard')
    @endif

    @if(isset($courses))
        @include('training.reports.users.courses')
    @endif

    @if(isset($tests))
        @include('training.reports.users.tests')
    @endif

@endsection
