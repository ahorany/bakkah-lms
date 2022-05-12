@extends('layouts.crm.index')

@section('table')
<?php
    $active_overview = '';
    $active_users  = '';
    $active_tests  = '';
    $active_scorms = '';
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
                <span class="mr-1 p-1 badge badge-dark" style="font-size: 0.8rem;">{{$user[0]->name}} | {{$user[0]->email}}  </span>
                <a href="{{route('training.usersReportOverview',['id'=>$user[0]->id])}}" class="group_buttons btn-sm {{$active_overview}}">Overview</a>
                <a  href="{{route('training.usersReportCourse',['id'=>$user[0]->id])}}" class="group_buttons btn-sm {{$active_users}}">Courses</a>
                <a href="{{route('training.usersReportTest',['id'=>$user[0]->id])}}" class="group_buttons btn-sm {{$active_tests}}">Tests</a>
                <a href="{{route('training.usersReportScorm',['user_id'=>$user[0]->id])}}" class="group_buttons btn-sm {{$active_scorms}}">SCORM</a>
            </div>
        </div>
    </div>

    @include($path)
@endsection
