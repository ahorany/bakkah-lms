<?php
use App\Models\Training\CourseRegistration;
?>
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.user')}}</th>
            <th class="">{{__('admin.user_type')}}</th>
            <th class="">{{__('admin.last_login')}}</th>

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
            <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->role_name) }} </span>
        </td>

        <td class="px-1">
            <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->last_login) }} </span>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
