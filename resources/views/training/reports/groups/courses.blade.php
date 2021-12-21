<?php
use App\Models\Training\CourseRegistration;
use Illuminate\Support\Facades\DB;
?>
<div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>

            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.course')}}</th>
            <th class="">{{__('admin.assigned_learners')}}</th>
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
            <span style="display: block;">{{ \App\Helpers\Lang::TransTitle($post->title) }}  </span>
        </td>
       <td>
            <?php
                $post_id = $post->id;
                $assigned_learners  = DB::table('course_groups')
                ->join('user_groups', function ($join) use($group_id,$post_id) {
                    $join->on('user_groups.group_id', '=', 'course_groups.group_id')
                        ->where('user_groups.group_id',$group_id)
                        ->where('course_groups.group_id',$group_id)
                        ->where('course_groups.course_id',$post_id);
                })
                ->count();
            ?>
             <span style="display: block;">{{ $assigned_learners }}  </span>
       </td>

        <td class="px-1">
            <span style="display: block;">{{ $post->PDUs }} </span>
        </td>

      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
