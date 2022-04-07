@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Course Reports')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')

<?php
    $ctive_overview = '';
    $ctive_users  = '';
    $ctive_tests  = '';
    $ctive_scorms = '';
    if(isset($overview))
    {
        $ctive_overview = 'active';
        $path = 'training.reports.courses.dashboard';
    }
    elseif(isset($users))
    {
        $ctive_users = 'active';
        $path = 'training.reports.courses.users';
    }
    elseif(isset($tests))
    {
        $ctive_tests = 'active';
        $path = 'training.reports.courses.tests';
    }
    elseif(isset($scorms))
    {
        $ctive_scorms = 'active';
        $path = 'training.reports.scorms.scorms';
    }
?>
    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-6">
                <span class="mr-1 p-1 badge badge-dark" style="font-size: 0.8rem;">{{$course->trans_title}}</span>
                <a href="{{route('training.coursesReportOverview',['id'=>$course_id])}}" class="group_buttons btn-sm {{$ctive_overview}}">Overview</a>
                <a href="{{route('training.coursesReportUser',['id'=>$course_id])}}" class="group_buttons btn-sm {{$ctive_users}}">Users</a>
                <a href="{{route('training.coursesReportTest',['id'=>$course_id])}}" class="group_buttons btn-sm {{$ctive_tests}}">Tests</a>
                {{-- <a href="{{route('training.scormsReportScorms',['course_id'=>$course_id])}}" class="group_buttons btn-sm {{$ctive_scorms}}">SCORMS</a> --}}
                {{-- <a href="{{route('training.course_users',['id'=>$course_id])}}" class="group_buttons btn-sm">Certificates</a> --}}
            </div>
        </div>
    </div>

        @include($path)


@endsection

