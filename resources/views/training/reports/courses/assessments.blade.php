<?php
use App\Models\Training\CourseRegistration;
?>

@section('useHead')
    <title>{{__('education.Course Users')}} | {{ __('home.DC_title') }}</title>
@endsection



@if(!is_null($user) && $user != '')
    <?php
        $active_all = '';
        $active_s  = '';

        if(isset($user[0]->id) && $show_all == 0)
        {
            $active_s = 'active';
        }
        else
        {
            $active_all = 'active';
        }
    ?>
    <a href="{{route('training.coursesAssessments',['id'=>$course[0]->id,'user_id'=>$user[0]->id])}}" class="group_buttons btn-sm {{$active_all}}" >All Users </a>
    <a href="{{route('training.coursesAssessments',['id'=>$course[0]->id,'user_id'=>$user[0]->id,'show_all'=>0])}}" class="group_buttons btn-sm {{$active_s}}">{{ \App\Helpers\Lang::TransTitle($user[0]->name) }} | {{$user[0]->email}} </a>
@endif
@include('training.reports.search.search_courses_assessments')

{{-- <form id="post-search" class="courses form-inline mb-4" method="get" action="{{route('training.coursesAssessments')}}">
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
</form> --}}

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
    <thead>
        <tr>
            <th class="">#</th>
            <th class="text-left">{{__('admin.name')}}</th>
            <th class="text-left">{{__('admin.email')}}</th>
            <th class="">{{__('admin.pre_assessment_score')}}</th>
            <th class="">{{__('admin.post_assessment_score')}}</th>
            <th class="">{{__('admin.knowledge_status')}}</th>
            @if($course[0]->training_option_id == 13)
                <th class="">{{__('admin.attendance_count')}}</th>
                <th class="text-left">{{__('admin.instructor')}}</th>
                <th class="text-left">{{__('admin.session_id')}}</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($assessments as $post)
            <tr data-id="{{$post->user_id}}">

                <td>
                    <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>

                <td class="text-left">
                    <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->user_name) }} </span>
                </td>
                <td class="text-left">
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
                        $badge = 'badge-lim';
                    else if($post->knowledge_status == 'Deceased')
                        $badge = 'badge-red';
                    else if($post->knowledge_status == 'Not Yet')
                        $badge = 'badge-blue';
                    @endphp
                    <span style="display: block;" class="{{$badge}}">{{ $post->knowledge_status }} </span>
                </td>
                @if($course[0]->training_option_id == 13)
                    <td>
                        <span style="display: block;">{{ $post->attendance_count }} </span>
                    </td>
                    <td class="text-left">
                        <span style="display: block;">{{ $post->trainer_name }} </span>
                    </td>
                    <td class="text-left">
                        <span class="badge-green" > {{ $post->s_id }}</span>
                    </td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{$paginator->render()}}
