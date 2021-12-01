@extends('layouts.crm.index')

@section('table')



    <div  class="course_info mb-3 card p-3">
        <div class="row">
            <div class="col-md-6">

                <a href="{{route('training.usersReport',['id'=>$user_id])}}" class="group_buttons btn-sm">Overview</a>
                <a  href="{{route('training.courseReport',['id'=>$user_id])}}" class="group_buttons btn-sm">Courses</a>
                <a href="{{route('training.course_users',['id'=>$user_id])}}" class="group_buttons btn-sm">Tests</a>
                <a href="{{route('training.course_users',['id'=>$user_id])}}" class="group_buttons btn-sm">Certificates</a>

            </div>
        </div>
    </div>
    @if(isset($overview))
        @include('training.users.dashboard')
    @endif

    @if(isset($courses))
        @include('training.reports.courses')
    @endif


@endsection
