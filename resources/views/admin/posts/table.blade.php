<div class="card">
  <div class="card-header">
    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $posts->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.title')}}</th>
            <th class="">{{__('admin.show_in_website')}}</th>

            @if($post_type=='education-slider' || $post_type=='modal-campaign' || $post_type=='navbar-campaign')
                <th class="">{{__('admin.country_id')}}</th>
            @endif
            <th class="">{{__('admin.most_read')}}</th>
            <th class="d-none d-sm-table-cell" style="width: 50px;">{{__('admin.trans')}}</th>
            <th class="d-none d-sm-table-cell" style="width: 200px;">{{__('admin.user')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($posts as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;">{{$post->title}}</span>
            {!!Builder::BtnGroupRows($post->title, $post->id, [], [
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
        <td class="d-sm-table-cell">
            <span class="td-title">{{$post->country->trans_name??null}}</span>
        </td>
        @if($post_type=='education-slider' || $post_type=='modal-campaign' || $post_type=='navbar-campaign')
          <td>
            <span class="td-title">{{$post->country->trans_name??null}}</span>
          </td>
        @endif
        <td class="d-none d-sm-table-cell">
        {!!$post->trans!!}
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
{{ $posts->appends(['post_type' => $post_type??null])->render() }}
