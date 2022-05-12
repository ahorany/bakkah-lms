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

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">#</th>
            <th class="">{{__('admin.course')}}</th>
            <th class="">{{__('admin.test')}}</th>
            <th class="">{{__('admin.date')}}</th>
            <th class="">{{__('admin.exam_mark')}}</th>
            <th class="">Pass Mark</th>{{-- {{__('admin.exam_pass')}} --}}
            <th class="">{{__('admin.trainee_mark')}}</th>
            <th class="">{{__('admin.result')}}</th>

        </tr>
      </thead>
      <tbody>
      @foreach($tests as $exam)
      <tr data-id="{{$exam->id}}">
        <td>
            <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>
        <td class="px-1">

            <a target="_blank" href="{{route('training.progressDetails',['user_id'=>$user[0]->id,'course_id'=>$exam->course_id,'back_page'=>'tests'])}}" class="btn-sm outline"><span style="display: block;" class="href">{{ \App\Helpers\Lang::TransTitle($exam->course_title) }} </span></a>

        </td>
        <td class="px-1">

            <a  target="_blank"  href="{{CustomRoute('training.exam_show',['content_id'=>$exam->content_id,'user_id'=>$user[0]->id,'back_page'=>'users_tests'] )}}" class="btn-sm outline"><span style="display: block;" class="href">{{ \App\Helpers\Lang::TransTitle($exam->content_title) }} </span></a>

        </td>
        <td class="px-1">
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
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{$paginator->render()}}
