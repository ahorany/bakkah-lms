<div class="card courses">
  <div class="card-header">

    {!!Builder::BtnGroupTable()!!}
    {!!Builder::TableAllPosts($count, $groups->count())!!}
  </div>
  <div class="card-body table-responsive p-0">
    <table class="table table-hover table-condensed">
      <thead>
        <tr>
            <th class="">{{__('admin.index')}}</th>
            <th class="">{{__('admin.name')}}</th>
            <th class="">{{__('admin.title')}}</th>
            <th class="img-table d-none d-sm-table-cell">{{__('admin.image')}}</th>
            <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            <th class="img-table d-none d-sm-table-cell text-center">{{__('admin.action')}}</th>

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

          <td class="d-none d-sm-table-cell">{!!Builder::UploadRow($group)!!}</td>
          <td class="d-none d-sm-table-cell">
            <span class="author">
              {!!$group->published_at!!}<br>
            </span>
          </td>
          <td class="d-none d-sm-table-cell text-center">
              {!!Builder::BtnGroupRows($group->name, $group->id, [], [
            'post'=>$group->id,
         ])!!}

              <div class="my-2">
                 <a class="green" href="{{route('training.group_users',['group_id' => $group->id])}}">users</a>
                 <a class="green" href="{{route('training.group_courses',['group_id' => $group->id])}}">courses</a>
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
