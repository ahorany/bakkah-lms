<div class="card">
    <div class="card-header">
        {{--Builder::BtnGroupTable()--}}
          @if(!isset($trash))
          {!! '<a href="'.route('admin.services.create', ['master_id'=>request()->master_id]).'" class="btn btn-sm btn-primary" title="'.__('admin.create').'"><i class="fa fa-plus"></i> '.__('admin.create').'</a>' !!}
              {!! '<a href="'.route('admin.services.index', ['master_id'=>request()->master_id, 'trash'=>'trash']).'" class="btn btn-sm btn-default" title="'.__('admin.trash').'"><i class="fa fa-trash"></i> '.__('admin.trash').'</a>' !!}
          @else
          {!! '<a href="'.route('admin.services.index', ['master_id'=>request()->master_id]).'" class="btn btn-sm btn-primary" title="'.__('admin.trash').'"><i class="fa fa-list"></i> '.__('admin.list').'</a>' !!}
        {!!Builder::TableAllPosts($count, $services->count())!!}
          @endif
      </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover table-condensed">
        <thead>
          <tr>
              <th class="">{{__('admin.index')}}</th>
              <th class="">{{__('admin.title')}}</th>
              <th class="">{{__('admin.excerpt')}}</th>
              <th class="">{{__('admin.percentage')}}</th>
              <th class="d-none d-sm-table-cell" style="width: 200px;">{{__('admin.user')}}</th>
          </tr>
        </thead>
        <tbody>
        @foreach($services as $service)
        <tr data-id="{{$service->id}}">
          <td>
            <span class="td-title">{{$loop->iteration}}</span>
          </td>
          <td>
              <span style="display: block;">{{$service->trans_title}}</span>
              {!!Builder::BtnGroupRows($service->trans_title, $service->id, [], [
                 'post'=>$service->id,
              ])!!}
          </td>
          <td>
            <span class="td-title">{{$service->trans_excerpt}}</span>
          </td>
          <td>
            <span class="td-title">{{$service->percentage}}</span>
          </td>
          <td class="d-none d-sm-table-cell author-cell">
            <span class="author">
              {!!$service->published_at!!}
            </span>
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!-- /.card-body -->
  {{-- $posts->appends(['post_type' => $post_type??null])->render() --}}
