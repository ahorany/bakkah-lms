<style>
.href{
    color:black;
}
</style>
<?php
use App\Models\Training\CourseRegistration;
?>

@section('useHead')
<title>{{__('education.User Tests')}} | {{ __('home.DC_title') }}</title>
@endsection




@if( $show_all == 1)
    <a href="{{route('training.usersReportScorm',['id'=>$user[0]->id,'export'=>1])}}" class="export btn-sm">{{__('admin.export')}} </a>
@else
    <a href="{{route('training.usersReportScorm',['id'=>$user[0]->id,'course_id'=>$course[0]->id,'export'=>1,'show_all'=>0])}}" class="export btn-sm">{{__('admin.export')}} </a>
@endif


@if(!is_null($course) && $course != '')
    <?php
        $active_all = '';
        $active_s  = '';

        if($show_all  == 1)
        {
            $active_all = 'active';
        }
        else
        {
            $active_s = 'active';
        }
    ?>
    <a href="{{route('training.usersReportScorm',['id'=>$user[0]->id,'course_id'=>$course[0]->id])}}" class="group_buttons btn-sm {{$active_all}}" >All Courses </a>
    <a href="{{route('training.usersReportScorm',['id'=>$user[0]->id,'course_id'=>$course[0]->id,'show_all'=>0])}}" class="group_buttons btn-sm {{$active_s}}">{{ \App\Helpers\Lang::TransTitle($course[0]->title) }} </a>
@endif


<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">#</th>
            <th class="text-left">{{__('admin.course')}}</th>
            <th class="text-left">{{__('admin.scorm')}}</th>
            <th class="">{{__('admin.date')}}</th>
            <th class="text-left">{{__('admin.progress')}}</th>
            <th class="">{{__('admin.score')}}</th>
            <th class=""></th>
        </tr>
      </thead>
      <tbody>

      @foreach($scorms as $post)
      <tr data-id="{{$post->id}}">
        <td>

          <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>

        <td class="px-1 text-left">
            {{-- <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->crtitle) }}  </span> --}}
            {{-- <a target="_blank" href="{{route('training.progressDetails',['user_id'=>$user[0]->id,'course_id'=>$post->course_id,'back_page'=>'tests'])}}" class="btn-sm outline"><span style="display: block;" class="href">{{ \App\Helpers\Lang::TransTitle($post->crtitle) }} </span></a> --}}
            <span style="display: block;" >{{ \App\Helpers\Lang::TransTitle($post->crtitle) }} </span>
        </td>

        <td class="px-1 text-left">
            {{ \App\Helpers\Lang::TransTitle($post->cotitle) }}
            {{-- <a href="{{CustomRoute('user.course_preview', $post->id)}}" target="_blank" class="href" >
                {{ \App\Helpers\Lang::TransTitle($post->cotitle) }}</a> --}}
        </td>

        <td class="px-1">
            <span class="td-title">{{$post->date}}</span>
        </td>

        <td>

            <?php

                $lesson_status = ucfirst($post->lesson_status);
                if($post->lesson_status == 'completed')
                    $badge = 'info';
                elseif($post->lesson_status == 'incomplete')
                    $badge = 'danger';
                elseif($post->lesson_status == 'not attempted')
                    $badge = 'warning';
                elseif($post->lesson_status == 'passed')
                    $badge = 'success';
            ?>

            <span class="d-block badge badge-{{$badge}} mb-1 ">
                {{$lesson_status}}
            </span>

        </td>

        <td>
            <span style="display: block;"> {{$post->score}}</span>
        </td>

        <td>
            <a class="nav-link cyan" target="_blank" href="{{route('training.scormUsers',['content_id'=>$post->id])}}" title="Users" style=" display: inline-block">
                @include('training.reports.svg_report.users')
                </a>
        </td>

      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{$paginator->render()}}
