<div class="card courses">
    <div class="card-header">
        <?php $create_role = false; ?>
        @can('roles.create')
                <?php $create_role = true; ?>
        @endcan
            {!!Builder::BtnGroupTable($create_role)!!}

        {!!Builder::TableAllPosts($count, $roles->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover table-condensed">
        <thead>
          <tr>
              <th class="">{{__('admin.index')}}</th>
              <th class="">{{__('admin.name')}}</th>
              <th class="">{{__('admin.pages')}}</th>
              <th class="d-sm-table-cell text-right px-5">{{__('admin.action')}}</th>
          </tr>
        </thead>
        <tbody>
        <?php $btn_roles = null; ?>
        @can('roles.edit')
              <?php  $btn_roles[] = 'Edit'; ?>
        @endcan

        @can('roles.delete')
                <?php   $btn_roles[] = 'Destroy' ?>
        @endcan

        @foreach($roles as $role)
            <tr data-id="{{$role->id}}">
            <td>
                <span class="td-title">{{$loop->iteration}}</span>
            </td>
            <td>
                <span style="display: block;">{{$role->name}}</span>

            </td>
            <td>
                @foreach ($role->permissions as $page)
                    <span style="font-size: 70%; padding: 3px 6px;" class="badge badge-success mb-1">{{ $page->title }}</span>
                @endforeach
            </td>
               <?php
                    $role_type_ids = [510,511,512];
                    if(in_array($role->role_type_id, $role_type_ids)){
                         if (isset($btn_roles[1])){
                              array_splice($btn_roles,1,1);
                         }
                    }

                ?>
                    <td class="d-sm-table-cell text-right">{!!Builder::BtnGroupRows($role->trans_name, $role->id, $btn_roles, [
                       'post'=>$role->id,
                    ])!!}</td>

            </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
<!-- /.card-body -->

{{ $roles->appends(['user_search' => request()->user_search??null])->render() }}
