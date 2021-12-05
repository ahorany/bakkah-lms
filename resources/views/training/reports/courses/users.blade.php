<?php
use App\Models\Training\CourseRegistration;
?>
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.progress')}}</th>
           {{--  <th class="">{{__('admin.score')}}</th>
            <th class="">{{__('admin.enrolled_on')}}</th>
            <th class="">{{__('admin.completion_date')}}</th>
            <th class="">{{__('admin.pdu')}}</th> --}}
        </tr>
      </thead>
      <tbody>
        @foreach($users as $post)
            <tr data-id="{{$post->id}}">
                <td>
                <span class="td-title px-1">{{$loop->iteration}}</span>
                </td>
                <td class="px-1">
                    <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->name) }} </span>

                </td>
                <td class="px-1">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" @if ($post->progress != null) style="width: {{$post->progress}}%;" @else style="width: 0%;" @endif aria-valuenow="{{$post->progress}}" aria-valuemin="0" aria-valuemax="100">@if ($post->progress != null) {{$post->progress}}% @else 0% @endif</div>
                    </div>
                </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  </div>
