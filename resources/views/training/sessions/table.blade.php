<?php
use App\Models\Training\CourseRegistration;
?>

<div class="card categories">
  <div class="card-header">
    {!!Builder::BtnGroupTable(true)!!}
    {!!Builder::TableAllPosts($count, $sessions->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.course_title')}}</th>
            <th class="">{{__('admin.date_from')}}</th>
            <th class="">{{__('admin.date_to')}}</th>
            <th class="">{{__('admin.ref_id')}}</th>
            <th class="text-right">{{__('admin.action')}}</th>
        </tr>
      </thead>
      <tbody>



{{--      @dd($sessions)--}}
      @foreach($sessions as $post)
      <tr data-id="{{$post->id}}">
            <td>
                <span class="td-title">{{$loop->iteration}}</span>
            </td>

          <td>
              <span style="display: block;" class="title">{{$post->course->trans_title ?? null}}</span>
          </td>

            <td>
                <span style="display: block;" class="title">{{$post->date_from ?? null}}</span>
            </td>

          <td>
              <span style="display: block;" class="title">{{$post->date_to ?? null}}</span>
          </td>

          <td>
              <span style="display: block;" class="title">{{$post->ref_id ?? null}}</span>
          </td>
            <td class="d-sm-table-cell text-right">
                {!!Builder::BtnGroupRows($post->trans_title, $post->id, [], [
                    'post'=>$post->id,
                ])!!}

            </td>
      </tr>

      @endforeach
      </tbody>
    </table>
  </div>
</div>

{{ $sessions->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null])->render() }}
