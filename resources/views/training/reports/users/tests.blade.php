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

@if(!is_null($course) && $course != '')
    <?php
        $active_all = '';
        $active_s  = '';

        if(isset($course[0]->id) && $show_all == 0)
        {
            $active_s = 'active';
        }
        else
        {
            $active_all = 'active';
        }
    ?>
    <a href="{{route('training.usersReportTest',['id'=>$user[0]->id,'course_id'=>$course[0]->id])}}" class="group_buttons btn-sm {{$active_all}}" >All Courses </a>
    <a href="{{route('training.usersReportTest',['id'=>$user[0]->id,'course_id'=>$course[0]->id,'show_all'=>0])}}" class="group_buttons btn-sm {{$active_s}}">{{ \App\Helpers\Lang::TransTitle($course[0]->title) }} </a>
@endif
@include('training.reports.search.search_users_tests')

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="text-left">{{__('admin.course')}}</th>
                <th class="text-left">{{__('admin.test')}}</th>
                <th class="">{{__('admin.date')}}</th>
                <th class="">{{__('admin.exam_mark')}}</th>
                <th class="">Pass Mark</th>{{-- {{__('admin.exam_pass')}} --}}
                <th class="">{{__('admin.trainee_mark')}}</th>
                <th class="">{{__('admin.result')}}</th>
                <th class=""></th>
            </tr>
        </thead>
      <tbody>
      @foreach($tests as $exam)
      {{-- @dd($exam->content_id) --}}
      <tr data-id="{{$exam->id}}">
        <td>
            <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>
        <td class="px-1 text-left" >

            {{-- <a target="_blank" href="{{route('training.progressDetails',['user_id'=>$user[0]->id,'course_id'=>$exam->course_id,'back_page'=>'tests'])}}" class="btn-sm outline"><span style="display: block;text-decoration:underline;" class="href">{{ \App\Helpers\Lang::TransTitle($exam->course_title) }} </span></a> --}}
            <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($exam->course_title) }} </span>

        </td>
        <td class="px-1 text-left">

            {{-- <a  target="_blank"  href="{{CustomRoute('training.exam_show',['content_id'=>$exam->content_id,'user_id'=>$user[0]->id,'back_page'=>'users_tests'] )}}" class="btn-sm outline"><span style="display: block;text-decoration:underline;" class="href">{{ \App\Helpers\Lang::TransTitle($exam->content_title) }} </span></a> --}}
            <span style="display: block;"">{{ \App\Helpers\Lang::TransTitle($exam->content_title) }} </span>

        </td>
        <td class="px-1 text-left"">
            <span class="td-title">{{$exam->time}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$exam->exam_mark}}</span>
        </td>
        <?php
        $pass_mark = $exam->pass_mark/100*$exam->exam_mark;
        ?>
        <td class="px-1">
            <span class="td-title">{{$pass_mark}}</span>
        </td>
        <td class="px-1">
            <span class="badge badge-info">{{$exam->exam_trainee_mark}} / {{$exam->exam_mark}}</span>
        </td>
        <td class="px-1">
            @if($exam->exam_trainee_mark >= $pass_mark)
                <span class="badge badge-success">{{__('admin.pass')}}</span>
            @else
                <span class="badge badge-danger">{{__('admin.fail')}}</span>
            @endif
        </td>
        <td>

            <a class="nav-link cyan" target="_blank" href="{{CustomRoute('training.exam_show',['content_id'=>$exam->content_id,'user_id'=>$user[0]->id] )}} " title="Preview" style=" display: inline-block">
                @include('training.reports.svg_report.preview')
            </a>

            <a class="nav-link cyan" target="_blank" href="{{route('training.testUsers',['exam_id'=>$exam->id,'content_id'=>$exam->content_id])}} " title="Users" style=" display: inline-block">
                @include('training.reports.svg_report.users')
                </a>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{$paginator->render()}}
