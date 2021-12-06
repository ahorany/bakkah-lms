@include('training.reports.groups.dashboard')
<div class="card courses">
  <div class="card-header">

    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $groups->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed text-center">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}} </th>
            <th class="">{{__('admin.name')}} </th>
            <th class="">{{__('admin.title')}} </th>
<<<<<<< HEAD
            <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}} </th>
            <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}} </th>
            <th class="img-table d-none d-sm-table-cell text-center">{{__('admin.action')}} </th>
=======
            {{-- <th class="">{{__('admin.image')}} </th>
            <th class="">{{__('admin.user')}} </th> --}}
            <th class="text-right">{{__('admin.action')}} </th>

>>>>>>> 13451af21dae647fd712c84ef350ad6f89bc4605
        </tr>
      </thead>
      <tbody>
      @foreach($groups as $group)
      <tr data-id="{{$group->id}}">
        <td>
          <span class="td-title">{{$loop->iteration}}</span>
        </td>
        <td>
            <span style="display: block;">{{$group->name}}</span>

        </td>

          <td>
              <span style="display: block;">{{$group->title}}</span>
          </td>

          {{-- <td class="">{!!Builder::UploadRow($group)!!}</td>
          <td class="">
            <span class="author">
              {!!$group->published_at!!}<br>
            </span>
          </td> --}}
          <td class="text-right">
              {!!Builder::BtnGroupRows($group->name, $group->id, [], [
            'post'=>$group->id,
         ])!!}
                <a href="{{route('training.groupReportOverview',['id'=>$group->id])}}" target="blank" class="cyan my-1" ><i class="fa fa-pencil"></i> Report</a>
              <div class="my-2">
                 <a class="green" href="{{route('training.group_users',['group_id' => $group->id])}}">Users</a>
                 <a class="green" href="{{route('training.group_courses',['group_id' => $group->id])}}">Courses</a>
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
{{ $groups->appends(['post_type' => $post_type??null, 'trash' => request()->trash??null])->render() }}
