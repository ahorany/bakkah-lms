<?php
use App\Models\Training\CourseRegistration;
?>

@section('useHead')
    <title>{{__('education.Course Users')}} | {{ __('home.DC_title') }}</title>
@endsection

<a href="{{route('training.coursesAssessments',['id'=>$course_id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}}</a>

<form id="post-search" class="courses form-inline mb-4" method="get" action="{{route('training.coursesAssessments')}}">
    <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header">
                <b>{{__('admin.search form')}}</b>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        {!! Builder::Hidden('trash') !!}
                        {!! Builder::Hidden('id', $course_id) !!}
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="session_id">{{__('admin.session_id')}}</label>
                                <select name="session_id" class="form-control" style="width:250px" data-show-flag="true" >
                                <option value="">Choose Value</option>
                                @foreach ($sessions as $key => $value)

                                <option value="{{ $value->id }}" {{ $session_id ==  $value->id ? "selected" :""}} >
                                    SID : {{$value->id }} | {{$value->date_from}} | {{$value->date_to}}
                                </option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div style="margin-top: 5px;">
                                {!! Builder::Submit('search', 'search', 'main-color', 'search') !!}
                                <button type="reset" class="cyan" >{{__('admin.clear')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
    <thead>
        <tr>
            <th class="">#</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.email')}}</th>
            <th class="">{{__('admin.pre_assessment_score')}}</th>
            <th class="">{{__('admin.post_assessment_score')}}</th>
            <th class="">{{__('admin.knowledge_status')}}</th>
            <th class="">{{__('admin.attendance_count')}}</th>
            <th class="">{{__('admin.instructor')}}</th>
            <th class="">{{__('admin.session_id')}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($assessments as $post)
            <tr data-id="{{$post->user_id}}">

                <td>
                    <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>

                <td class="px-1">
                    <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->user_name) }} </span>
                </td>
                <td class="px-1">
                    <span style="display: block;">{{ $post->user_email }} </span>
                </td>

                <td class="px-1">
                    <span style="display: block;">{{ $post->pre_mark }} </span>
                </td>

                <td>
                    <span style="display: block;">{{ $post->post_mark }} </span>
                </td>
                <td>
                    @php
                    if($post->knowledge_status == 'Improved' )
                        $badge = 'badge-green';
                    else if($post->knowledge_status == 'Constant')
                        $badge = 'badge-yellow';
                    else
                        $badge = 'badge-red';
                    @endphp
                    <span style="display: block;" class="{{$badge}}">{{ $post->knowledge_status }} </span>
                </td>
                <td>
                    <span style="display: block;">{{ $post->attendance_count }} </span>
                </td>
                <td>
                    <span style="display: block;">{{ $post->trainer_name }} </span>
                </td>
                <td>
                    <span style="display: block;">{{ $post->s_id }} </span>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{$paginator->render()}}
