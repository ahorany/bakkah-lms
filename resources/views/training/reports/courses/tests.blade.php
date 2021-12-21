<?php
use App\Models\Training\CourseRegistration;
use Illuminate\Support\Facades\DB;
?>

@section('useHead')
    <title>{{__('education.Course Tests')}} | {{ __('home.DC_title') }}</title>
@endsection

<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.test')}}</th>
            <th class="">{{__('admin.completed')}}</th>
            <th class="">{{__('admin.passed')}}</th>

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
       <td>
            <?php
                $completed = DB::table('user_exams')
                            ->where('exam_id',$post->id)
                            ->where('status',1)
                            ->count(DB::raw('DISTINCT status,user_id'));
            ?>
             <span style="display: block;">{{ $completed }} </span>
       </td>
       <td>
        <?php

        ?>
        </td>

      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
