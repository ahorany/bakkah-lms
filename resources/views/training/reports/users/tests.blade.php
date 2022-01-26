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
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.test')}}</th>
            <th class="">{{__('admin.course')}}</th>
            <th class="">{{__('admin.date')}}</th>
            <th class="">{{__('admin.exam_mark')}}</th>
            <th class="">{{__('admin.exam_pass')}}</th>
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
            <span style="display: block;">{{ $exam->content_title }} </span>
        </td>
        <td class="px-1">
            <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($exam->course_title) }} </span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$exam->time}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$exam->exam_mark}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$exam->pass_mark}}%</span>
        </td>
        <td class="px-1">
            <span class="badge badge-info">{{$exam->exam_trainee_mark}} / {{$exam->exam_mark}}</span>
        </td>
        <td class="px-1">
            @if(($exam->exam_trainee_mark/$exam->exam_mark) >= $exam->pass_mark)
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
