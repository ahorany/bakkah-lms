<style>
    .href{
        color: black;
    }
</style>

<?php
use App\Models\Training\CourseRegistration;
?>

@section('useHead')
    <title>{{__('education.Course Users')}} | {{ __('home.DC_title') }}</title>
@endsection



{{-- @if( $show_all == 1)
    <a href="{{route('training.coursesReportUser',['id'=>$course[0]->id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}} </a>
@else
    <a href="{{route('training.coursesReportUser',['id'=>$course[0]->id,'user_id'=>$user[0]->id,'export'=>1,'show_all'=>0])}}" class="export btn-sm">{{__('admin.export')}} </a>
@endif --}}


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
    <a href="{{route('training.coursesReportUser',['id'=>$course[0]->id,'user_id'=>$user[0]->id])}}" class="group_buttons btn-sm {{$active_all}}" >All Users </a>
    <a href="{{route('training.coursesReportUser',['id'=>$course[0]->id,'user_id'=>$user[0]->id,'show_all'=>0])}}" class="group_buttons btn-sm {{$active_s}}">{{ \App\Helpers\Lang::TransTitle($user[0]->name) }} | {{$user[0]->email}} </a>
@endif
@include('training.reports.search.search_courses_users')
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">#</th>
            <th class="text-left">{{__('admin.user')}}</th>
            <th class="text-left">{{__('admin.email')}}</th>
            <th class="text-left">{{__('admin.progress')}}</th>
            {{--  <th class="">{{__('admin.score')}}</th>--}}
            <th class="">{{__('admin.enrolled_date')}}</th>
            <th class="">{{__('admin.Last_login_date')}}</th>
            {{-- <th class="">{{__('admin.completion_date')}}</th>
            <th class="">{{__('admin.pdu')}}</th> --}}
            @if($course[0]->training_option_id == 13)
                <th class="text-left">{{__('admin.session')}}</th>
            @endif
            <th class="">{{__('admin.user_type')}}</th>
            <th class=""></th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $post)
            <tr data-id="{{$post->id}}">

                <td>
                    <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>

                <td class="px-1 text-left">
                    <span style="display: block;" class="href">{{ \App\Helpers\Lang::TransTitle($post->name) }} </span>
                </td>
                <td class="px-1 text-left">
                    <span style="display: block;">{{$post->email }} </span>
                </td>
                <td class="px-1 text-left">
                    @if($post->role_type_id == 512)
                        <div class="progress progress-new">
                            <div class="progress-bar" role="progressbar" @if ($post->progress != null) style="width: {{$post->progress}}%;" @else style="width: 0%;" @endif aria-valuenow="{{$post->progress}}" aria-valuemin="0" aria-valuemax="100"></div>
                            <span >@if ($post->progress != null) {{$post->progress}}% @else 0% @endif</span>
                        </div>
                    @endif
                </td>
                <td class="px-1 text-left">
                    <span style="display: block;">{{$post->enrolled_date }} </span>
                </td>
                <td class="px-1 text-left">
                    <span style="display: block;">{{$post->last_login }} </span>
                </td>
                @if($course[0]->training_option_id == 13)
                    <td class="text-left">
                        <span class="badge-green" > {{$post->date_from?$post->date_from.' - ':''}}   {{$post->date_to}}</span>
                    </td>
                @endif
                <td>

                    @if($post->role_type_id == 511)
                        <span class="badge-pink" >
                    @elseif($post->role_type_id == 512)
                        <span class="badge-blue">
                    @endif
                    {{\App\Helpers\Lang::TransTitle($post->c_name)}}</span>
                </td>

                <td class="text-left">
                    @if($post->role_type_id == 512)
                        <a href="{{route('training.progressDetails',['user_id'=>$post->id,'course_id'=>$course_id,'preview'=>'true'])}}" class="nav-link cyan" style=" display: inline-block" target="_blank" title="Progress">
                            @include('training.reports.svg_report.progress')
                        </a>

                        <a href="{{route('training.usersReportCourse',['id'=>$post->id,'course_id'=>$course_id,'show_all'=>0])}}"  target="_blank" class="nav-link cyan" title="Courses" style=" display: inline-block">
                            @include('training.reports.svg_report.courses')

                        </a>
                        @if(isset($post->progress) && ($post->progress >= $post->complete_progress ))
                            <a href="{{route('training.certificates.certificate_dynamic', ['course_registration_id'=> $post->c_reg_id ] )}}"
                                target="_blank" class="nav-link cyan"  title="Certificate" style=" display: inline-block">
                                @include('training.reports.svg_report.certificate')

                            </a>
                        @endif
                    @endif

                </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  {{$paginator->render()}}

