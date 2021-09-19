<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $testimonials->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.name')}}</th>
                <th class="">{{__('admin.course_id')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($testimonials as $post)
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->userId->trans_name??null}}</span>
                        {!!Builder::BtnGroupRows($post->userId->trans_name??null, $post->id, [], [
                           'post'=>$post->id,
                        ])!!}
                    </td>
                    <td>
                        <span style="display: block;">{{$post->course->trans_title}}</span>
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
{{ $testimonials->appends(['post_type'=>$post_type])->render() }}
