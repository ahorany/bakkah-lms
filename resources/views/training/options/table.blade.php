<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $options->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th>index</th>
                <th class="">{{__('admin.title')}}</th>

                <th class="">{{__('admin.excerpt')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($options as $option)
                <tr data-id="{{$option->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$option->trans_title}}</span>
                        {!!Builder::BtnGroupRows($option->trans_title, $option->id, [], [
                           'post'=>$option->id,
                        ])!!}
                    </td>
                    <td><span>{{$option->trans_excerpt}}</span></td>
                    <td class="d-none d-sm-table-cell">
          <span class="author">
            {!!$option->published_at!!}<br>
          </span>
                    </td>
                    <td class="d-none d-sm-table-cell">{!!Builder::UploadRow($option)!!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->
{{--{{ $options->render() }}--}}
