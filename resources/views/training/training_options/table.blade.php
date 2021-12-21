<div class="card">
  <div class="card-header">
    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $training_options->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.title')}}</th>
            <th class="">{{__('training.course_name')}}</th>
            <th class="">{{__('training.lms_id')}}</th>
            <th class="">{{__('training.lms_course_id')}}</th>
            {{-- <th class="">{{__('training.evaluation_api_code')}}</th> --}}
            <th class="img-table d-none d-sm-table-cell">{{__('admin.action')}}</th>
            <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($training_options as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;">{{$post->constant->trans_name??null}}</span>
            
        </td>
        <td>
            <span style="display: block;">{{$post->course->trans_title??null}}</span>
        </td>
        <td>
            <span style="display: block;">{{$post->lms->en_name??null}}</span>
        </td>
        <td>
            <span style="display: block;">{{$post->lms_course_id??null}}</span>
        </td>
        <td>
            {!!Builder::BtnGroupRows($post->constant->trans_name??null, $post->id, [], [
               'post'=>$post->id,
            ])!!}
        </td>
        <td class="d-none d-sm-table-cell">
          <span class="author">
            {!!$post->published_at!!}
          </span>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->
{{ $training_options->appends(['training_option_id' => request()->training_option_id ?? -1])->render() }}
