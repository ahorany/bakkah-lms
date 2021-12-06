@extends('layouts.crm.index')

@section('table')

    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-6">

                <a href="{{route('training.groupReportOverview',['id'=>$group_id])}}" class="group_buttons btn-sm">Overview</a>
                <a  href="{{route('training.groupsReportUser',['id'=>$group_id])}}" class="group_buttons btn-sm">Users</a>
                <a href="{{route('training.groupsReporcourse',['id'=>$group_id])}}" class="group_buttons btn-sm">courses</a>
                {{-- <a href="{{route('training.course_users',['id'=>$group_id])}}" class="group_buttons btn-sm">Certificates</a> --}}

            </div>
        </div>
    </div>

    @if(isset($overview))
        @include('training.reports.groups.dashboard')
    @endif

    @if(isset($users))
        @include('training.reports.groups.users')
    @endif

    @if(isset($tests))
        @include('training.reports.groups.courses')
    @endif

@endsection
