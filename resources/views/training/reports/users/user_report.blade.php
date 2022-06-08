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
            <div class="col-md-12">
                <span class="mr-1 p-1 badge badge-dark" style="font-size: 0.8rem;">{{$user[0]->name??null}} | {{$user[0]->email??null}}  </span>
                <a href="{{route('training.usersReportOverview',['id'=>$user[0]->id??null,'course_id'=>$course[0]->id??null])}}" class="group_buttons btn-sm {{$active_overview}}">Overview </a>
                <a href="{{route('training.usersReportCourse',['id'=>$user[0]->id??null,'course_id'=>$course[0]->id??null,'show_all'=>$show_all??null])}}" class="group_buttons btn-sm {{$active_users}}">Courses </a>
                <a href="{{route('training.usersReportTest',['id'=>$user[0]->id??null,'course_id'=>$course[0]->id??null,'show_all'=>$show_all??null])}}" class="group_buttons btn-sm {{$active_tests}}">Tests </a>
                <a href="{{route('training.usersReportScorm',['id'=>$user[0]->id??null,'course_id'=>$course[0]->id??null,'show_all'=>$show_all??null])}}" class="group_buttons btn-sm {{$active_scorms}}">SCORM </a>
            </div>
        </div>
    </div>

    @include($path)
@endsection
