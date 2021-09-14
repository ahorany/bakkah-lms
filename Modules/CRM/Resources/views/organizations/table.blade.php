<div class="card">
    <div class="card-header">
        {!!Builder::SetNameSpace('')!!}
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $organizations->count())!!}
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.title')}}</th>
                <th class="">{{__('admin.mobile')}}</th>
                <th class="">{{__('admin.email')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($organizations as $organization)
                <tr data-id="{{$organization->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$organization->trans_title}}</span>
                        {!!Builder::BtnGroupRows($organization->trans_title, $organization->id, ['edit', 'destroy'], [
                           'post'=>$organization->id,
                        ])!!}
                    </td>
                    <td>
                        <span class="td-title">{{$organization->mobile}}</span>
                    </td>
                    <td>
                        <span class="td-title">{{$organization->email}}</span>
                    </td>
                    <td class="d-none d-sm-table-cell">
                      <span class="author">
                        {!!$organization->published_at!!}
                      </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->
{{ $organizations->render() }}
