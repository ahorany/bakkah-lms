<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $contacts->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.title')}}</th>
                <th class="">{{__('admin.email')}}</th>
                <th class="">{{__('admin.mobile')}}</th>
                <th class="">{{__('admin.message')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($contacts as $post)
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->name}}</span>
                        {!!Builder::BtnGroupRows($post->name, $post->id, [], [
                           'post'=>$post->id,
                        ])!!}
                    </td>
                    <td>
                        <span class="td-title">{{$post->email}}</span>
                    </td>
                    <td>
                        <span class="td-title">{{$post->mobile}}</span>
                    </td>
                    <td>
                        <span class="td-title">{!! $post->message !!}</span>
                    </td>
                    <td class="d-none d-sm-table-cell">
                      <span class="author">
                        {!!$post->published_at!!}
                      </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->
{{ $contacts->render() }}
