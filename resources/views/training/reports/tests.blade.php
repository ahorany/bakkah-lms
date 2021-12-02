<?php
use App\Models\Training\CourseRegistration;
?>
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
            <th class="">{{__('admin.attempt_mark')}}</th>
            <th class="">{{__('admin.result')}}</th>

        </tr>
      </thead>
      <tbody>
      @foreach($tests as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>
        <td class="px-1">
            <span style="display: block;">{{ $post->content_title }} </span>
        </td>
        <td class="px-1">
            <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->course_title) }} </span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->time}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->exam_mark}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->pass_mark}}</span>
        </td>
        <td class="px-1">
            <span class="badge badge-secondary">{{$post->mark}}</span>
            @if($post->mark >= $post->pass_mark)
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
