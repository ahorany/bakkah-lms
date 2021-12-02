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
            <th class="">{{__('admin.score')}}</th>
            <th class="">{{__('admin.enrolled_on')}}</th>
            <th class="">{{__('admin.completion_date')}}</th>
            <th class="">{{__('admin.pdu')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($courses as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title px-1">{{$loop->iteration}}</span>
        </td>
        <td class="px-1">
            <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->title) }} </span>

        </td>
        <td class="px-1">
            <?php
                // $reg = CourseRegistration::where('user_id',$user_id)->where('course_id',$post->id)->first();
                // dd($post->id);
            ?>
            <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{$post->progress}}%;" aria-valuenow="{{$post->progress}}" aria-valuemin="0" aria-valuemax="100">{{$post->progress}}%</div>
              </div>
        </td>
        <td class="px-1">
            <span class="badge badge-warning">{{$post->score}}</span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->created_at}}</span>
        </td>
        <td class="px-1">
            <span class="td-title"></span>
        </td>
        <td class="px-1">
            <span class="td-title">{{$post->PDUs}}</span>
        </td>

      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
