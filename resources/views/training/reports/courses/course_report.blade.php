@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Course Reports')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')

<?php
    $active_overview    = '';
    $active_users       = '';
    $active_tests       = '';
    $active_scorms      = '';
    $active_assessments = '';

    if(isset($overview))
    {
        $active_overview = 'active';
        $path = 'training.reports.courses.dashboard';
    }
    elseif(isset($users))
    {
        $active_users = 'active';
        $path = 'training.reports.courses.users';
    }
    elseif(isset($tests))
    {
        $active_tests = 'active';
        $path = 'training.reports.courses.tests';
    }
    elseif(isset($scorms))
    {
        $active_scorms = 'active';
        $path = 'training.reports.courses.scorms';
    }
    elseif(isset($assessments))
    {
        $active_assessments = 'active';
        $path = 'training.reports.courses.assessments';
    }
?>
    @php
    $type = [
        '11' => 'self-paced',
        '13' => 'live-online',
        '353' => 'exam-simulators',
        '383' => 'instructor-led',
    ];
    @endphp
    <div class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-12">
                <span class="mr-1 p-1 badge badge-dark" style="font-size: 0.8rem;"> {{ \App\Helpers\Lang::TransTitle($course[0]->title) }} </span> <span class="mr-1 p-1 badge {{ $type[$course[0]->training_option_id] }}" style="font-size: 0.8rem;">  {{\App\Helpers\Lang::TransTitle($course[0]->const_name)}}</span>
                <a href="{{route('training.coursesReportOverview',['id'=>$course_id,'user_id'=>$user[0]->id??null])}}" class="group_buttons btn-sm {{$active_overview}}">Overview</a>
                <a href="{{route('training.coursesReportUser',['id'=>$course_id,'user_id'=>$user[0]->id??null,'show_all'=>$show_all??null])}}" class="group_buttons btn-sm {{$active_users}}">Users</a>
                <a href="{{route('training.coursesReportTest',['id'=>$course_id,'user_id'=>$user[0]->id??null,'show_all'=>$show_all??null])}}" class="group_buttons btn-sm {{$active_tests}}">Tests</a>
                <a href="{{route('training.coursesReportScorm',['id'=>$course_id,'user_id'=>$user[0]->id??null,'show_all'=>$show_all??null])}}" class="group_buttons btn-sm {{$active_scorms}}">SCORMS</a>
                {{-- <a href="{{route('training.course_users',['id'=>$course_id,'user_id'=>$user[0]->id??null,'show_all'=>$show_all??null])}}" class="group_buttons btn-sm">Certificates</a> --}}
                <a href="{{route('training.coursesAssessments',['id'=>$course_id,'user_id'=>$user[0]->id??null,'show_all'=>$show_all??null])}}" class="group_buttons btn-sm {{$active_assessments}}">Assessments</a>
            </div>
        </div>
    </div>

    @include($path)

@endsection
