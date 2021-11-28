
<form action="{{ route('training.importQuestions') }}" method="POST" enctype="multipart/form-data" class="col-md-5">
    @csrf
        {!!Builder::File('file', 'file', null, ['col'=>'col-md-8'])!!}
        {!!Builder::Submit('importQuestions', 'import_questions', 'btn-success mx-1 export-btn py-1 px-2', null, [
            'icon'=>'far fa-file-excel',
        ])!!}
</form>

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
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}}</th>
            <th class="img-table d-none d-sm-table-cell">{{__('admin.action')}}</th>
            <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
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

        </td>
        <td class="d-sm-table-cell">{!!Builder::UploadRow($post)!!}</td>

        <td>{!!Builder::BtnGroupRows($post->trans_title, $post->id, [], [
               'post'=>$post->id,
            ])!!}
        </td>
        <td class="d-sm-table-cell" style="font-size: 13px;">
          <span class="author">
            {!!$post->published_at!!}<br>
          </span>
        </td>
      </tr>

      @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->
{{-- {{ $courses->render() }} --}}
{{ $courses->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null, 'course_search' => request()->course_search??null, 'category_id' => request()->category_id??-1, 'show_in_website' => request()->show_in_website??null])->render() }}
