<div class="card">
  <div class="card-header">
      {{-- {!!Builder::SetBtnParam([
          'ppm'=>1,
          'ppm111'=>2,
      ])!!} --}}
    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $courses->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($courses as $post)
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
{{-- {{ $courses->render() }} --}}
{{ $courses->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null, 'course_search' => request()->course_search??null, 'category_id' => request()->category_id??-1, 'show_in_website' => request()->show_in_website??null])->render() }}
