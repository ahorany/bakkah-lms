<div class="card">
  <div class="card-header">
    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $webinars->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.show_in_website')}}</th>
            <th width="8%" class="">{{__('admin.session_start_time')}}</th>
            <th width="8%" class="">{{__('admin.session_end_time')}}</th>
            <th class="">{{__('admin.zoom_link')}}</th>
            <th class="">{{__('admin.video_link')}}</th>
            <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($webinars as $post)
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
        <td class="d-sm-table-cell">
            <span>
                @php
                    $t = '<i style="color: #0fc10f; font-size: 25px;" class="fas fa-check"></i>';
                    $f = '<i style="color: #f31f1f; font-size: 25px;" class="fas fa-times"></i>';
                @endphp
                {!!$post->show_in_website==1?$t:$f!!}
            </span>
        </td>
        <td>
            <span class="light" style="display: block;">{{$post->session_start_time}}</span>
        </td>
        <td>
            <span class="light" style="display: block;">{{$post->session_end_time}}</span>
        </td>
        <td>
            <span class="light" style="display: block;"><a href="{!! $post->zoom_link !!}" target="_blank">{!! $post->zoom_link !!}</a></span>
        </td>
        <td>
            <span class="light" style="display: block;"><a href="{!! $post->video_link !!}" target="_blank">{!! $post->video_link !!}</a></span>
        </td>
        <td class="d-none d-sm-table-cell">
          <span class="author">
            {!!$post->published_at!!}<br>
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
{{ $webinars->render() }}
