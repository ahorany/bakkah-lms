<div class="card courses">
    <div class="card-header">
      {!!Builder::BtnGroupTable()!!}
      {!!Builder::TableAllPosts($count, $roles->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover table-condensed">
        <thead>
          <tr>
              <th class="">{{__('admin.index')}}</th>
              <th class="">{{__('admin.name')}}</th>
              <th class="">{{__('admin.pages')}}</th>
              <th class="d-none d-sm-table-cell text-right px-5">{{__('admin.action')}}</th>
          </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
        <tr data-id="{{$role->id}}">
          <td>
            <span class="td-title">{{$loop->iteration}}</span>
          </td>
          <td>
              <span style="display: block;">{{$role->trans_name}}</span>

          </td>
          <td>
              @foreach ($role->infrastructures as $page)
                <span style="font-size: 70%; padding: 3px 6px;" class="badge badge-success mb-1">{{ $page->trans_title }}</span>
              @endforeach
          </td>
          <td class="d-none d-sm-table-cell text-right">{!!Builder::BtnGroupRows($role->trans_name, $role->id, [], [
            'post'=>$role->id,
         ])!!}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
<!-- /.card-body -->
{{-- {{ $roles->render() }} --}}
{{ $roles->appends(['user_search' => request()->user_search??null])->render() }}
