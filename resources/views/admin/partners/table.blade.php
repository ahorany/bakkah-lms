<div class="card">
  <div class="card-header">
    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $partners->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed clients-table">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.title')}}</th>
            <th style="width: 200px;">{{__('admin.Position')}}</th>
            <th style="width: 150px;">{{__('admin.show_in_home')}}</th>
            <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($partners as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;">{{$post->trans_name}}</span>
            {!!Builder::BtnGroupRows($post->trans_name, $post->id, [], [
               'post'=>$post->id,
            ])!!}
        </td>
          <td>
              <span class="badge badge-pill badge-success">{{$post->in_education==1?__('admin.in_education'):''}}</span>
              <span class="badge badge-pill badge-info">{{$post->in_consulting==1?__('admin.in_consulting'):''}}</span>
          </td>
          <td>
              {!! $post->show_in_home==1?'<span class="badge badge-pill badge-success">Yes</span>':'<span class="badge badge-pill badge-danger">No</span>'!!}
          </td>
        <td class="d-none d-sm-table-cell">
          <span class="author">
            {!!$post->published_at!!}
          </span>
        </td>
        <td class="d-none d-sm-table-cell">{!!Builder::UploadRow($post)!!}</td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->
{{ $partners->appends(['post_type'=>$post_type, 'title_search' => request()->title_search ?? null, 'in_education'=>request()->in_education, 'in_consulting'=>request()->in_consulting, 'show_in_home'=>request()->show_in_home])->render() }}
