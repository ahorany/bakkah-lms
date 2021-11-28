<div class="card">
  <div class="card-header">
    {{--Builder::BtnGroupTable()--}}
      @if(!isset($trash))
      {!! '<a href="'.route('admin.details.create', ['master_id'=>request()->master_id]).'" class="btn btn-sm btn-primary" title="'.__('admin.create').'"><i class="fa fa-plus"></i> '.__('admin.create').'</a>' !!}
          {!! '<a href="'.route('admin.details.index', ['master_id'=>request()->master_id, 'trash'=>'trash']).'" class="btn btn-sm btn-default" title="'.__('admin.trash').'"><i class="fa fa-trash"></i> '.__('admin.trash').'</a>' !!}
      @else
      {!! '<a href="'.route('admin.details.index', ['master_id'=>request()->master_id]).'" class="btn btn-sm btn-primary" title="'.__('admin.trash').'"><i class="fa fa-list"></i> '.__('admin.list').'</a>' !!}
    {!!Builder::TableAllPosts($count, $details->count())!!}
      @endif
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.title')}}</th>
            <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($details as $post)
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
{{ $details->render() }}
