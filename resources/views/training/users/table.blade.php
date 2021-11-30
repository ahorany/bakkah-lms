@section('style')
<style>
    .card-header span{
        color: #fff !important;
    }
</style>
@endsection
<div class="card courses">
  <div class="card-header">
    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $users->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.email')}}</th>
            <th class="">{{__('admin.mobile')}}</th>
            <th class="">{{__('admin.job_title')}}</th>
            <th class="">{{__('admin.company')}}</th>
            <th class="">{{__('admin.gender_id')}}</th>
            <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}}</th>
            <th class="d-none d-sm-table-cell text-center">{{__('admin.action')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($users as $post)
      <tr data-id="{{$post->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;">{{$post->trans_name}}</span>

        </td>
        <td>
            <span class="td-title">{{$post->email??null}}</span>
        </td>
        <td>
            <span class="td-title">{{$post->mobile??null}}</span>
        </td>
        <td>
            <span class="td-title">{{$post->job_title??null}}</span>
        </td>
        <td>
            <span class="td-title">{{$post->company??null}}</span>
        </td>
        <td>
            <span class="td-title">{{$post->gender->en_name??null}}</span>
        </td>
        {{--<td class="d-none d-sm-table-cell">
          <span class="author">
            {!!$post->published_at!!}
          </span>
        </td>--}}
        <td class="d-none d-sm-table-cell">{!!Builder::UploadRow($post)!!}</td>
        <td class="d-none d-sm-table-cell text-right pl-0">{!!Builder::BtnGroupRows($post->trans_name, $post->id, [], [
            'post'=>$post->id,
         ])!!}</td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->
{{-- {{ $users->render() }} --}}
{{ $users->appends([
    'user_search' => request()->user_search??null,
    'post_type' => $post_type
    ])->render() }}
