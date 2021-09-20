<div class="card">
  <div class="card-header">
    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $messages->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.title')}}</th>
            <th class="d-none d-sm-table-cell">{{__('admin.user')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($messages as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;">{{$post->trans_title}}</span>

            {!!Builder::BtnGroupRows($post->trans_title, $post->id, [], [
               'post'=>$post->id,
            ])!!}
        </td>
        <td class="d-none d-sm-table-cell author-cell">
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
{{ $messages->appends(['post_type' => $post_type??null])->render() }}