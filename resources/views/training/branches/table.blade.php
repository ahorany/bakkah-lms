<div class="card courses">
  <div class="card-header">

    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $branches->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}} </th>
            <th class="">{{__('admin.name')}} </th>
            <th class="">{{__('admin.title')}} </th>
            {{-- <th class="">{{__('admin.image')}} </th>
            <th class="">{{__('admin.user')}} </th> --}}
            <th class="text-right">{{__('admin.action')}} </th>

        </tr>
      </thead>
      <tbody>
      @foreach($branches as $branche)
      <tr data-id="{{$branche->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;">{{$branche->name}}</span>

        </td>

          <td>
              <span style="display: block;">{{$branche->title}}</span>
          </td>

          {{-- <td class="">{!!Builder::UploadRow($group)!!}</td>
          <td class="">
            <span class="author">
              {!!$group->published_at!!}<br>
            </span>
          </td> --}}
          <td class="text-right">
              {!!Builder::BtnGroupRows($branche->name, $branche->id, [], [
            'post'=>$branche->id,
         ])!!}
{{--                <a href="{{route('training.groupReportOverview',['id'=>$branche->id])}}" target="blank" class="cyan my-1" ><i class="fa fa-pencil"></i> Report</a>--}}
{{--              <div class="my-2">--}}
{{--                 <a class="green" href="{{route('training.group_users',['group_id' => $branche->id])}}">Users</a>--}}
{{--                 <a class="green" href="{{route('training.group_courses',['group_id' => $branche->id])}}">Courses</a>--}}
{{--              </div>--}}
          </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- /.card-body -->
{{-- {{ $courses->render() }} --}}
{{ $branches->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null])->render() }}
