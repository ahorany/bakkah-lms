@extends('layouts.crm.index')

@section('table')
<?php
    $ctive_overview = '';
    $ctive_users  = '';
    $ctive_tests  = '';
    $ctive_scorms = '';
    if(isset($overview))
    {
        $active_overview = 'active';
        $path = 'training.reports.users.dashboard';
    }
    elseif(isset($courses))
    {
        $active_users = 'active';
        $path = 'training.reports.users.courses';
    }
    elseif(isset($tests))
    {
        $active_tests = 'active';
        $path = 'training.reports.users.tests';
    }
    elseif(isset($scorms))
    {
        $active_scorms = 'active';
        $path = 'training.reports.users.scorms';
    }
?>

    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-6">
                <span class="mr-1 p-1 badge badge-dark" style="font-size: 0.8rem;">{{$user->trans_name}}</span>
                <a href="{{route('training.usersReportOverview',['id'=>$user_id])}}" class="group_buttons btn-sm {{$ctive_overview}}">Overview</a>
                <a  href="{{route('training.usersReportCourse',['id'=>$user_id])}}" class="group_buttons btn-sm {{$ctive_users}}">Courses</a>
                <a href="{{route('training.usersReportTest',['id'=>$user_id])}}" class="group_buttons btn-sm {{$ctive_tests}}">Tests</a>
                {{-- <a href="{{route('training.usersReportScorm',['user_id'=>$user_id])}}" class="group_buttons btn-sm {{$ctive_scorms}}">SCORM</a> --}}
            </div>
        </div>
    </div>

    @include($path)
@endsection
