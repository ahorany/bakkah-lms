<div class="card courses">
  <div class="card-header">
      {{-- {!!Builder::SetBtnParam([
          'ppm'=>1,
          'ppm111'=>2,
      ])!!} --}}
      @if(!checkUserIsTrainee())
       {!!Builder::BtnGroupTable()!!}
      @endif
    {!!Builder::TableAllPosts($count, $courses->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="col-md-1">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="img-table d-none d-sm-table-cell col-md-1">{{__('admin.image')}}</th>
            {{-- <th class="d-none d-sm-table-cell user-td col-md-2">{{__('admin.user')}}</th> --}}
            <th class="text-center col-md-3" scope="col">{{__('admin.action')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($courses as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;" class="title">{{$post->trans_title}}  &nbsp;&nbsp;&nbsp;&nbsp; {{$post->code}}</span>

        </td>
        <td class="d-sm-table-cell">{!!Builder::UploadRow($post)!!}</td>
        {{-- <td class="d-sm-table-cell" style="font-size: 13px;">
            <span class="author">
              {!!$post->published_at!!}<br>
            </span>
        </td> --}}

          <td class="d-sm-table-cell text-right">
              @if(!checkUserIsTrainee())
                {!!Builder::BtnGroupRows($post->trans_title, $post->id, [], [
                    'post'=>$post->id,
                ])!!}
              @endif
                <div class="my-2">
                    <a href="{{route('training.contents',['course_id'=>$post->id])}}" class="btn-sm save">Contents</a>
                    <a href="{{route('training.units',['course_id'=>$post->id])}}" class="btn-sm save">Units</a>
                    <a href="{{route('training.course_users',['course_id'=>$post->id])}}" class="btn-sm save">Users</a>
                </div>
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
