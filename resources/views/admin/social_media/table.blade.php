<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $social_media->count())!!}

    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.title')}}</th>
                <th class="">{{__('admin.description')}}</th>
                <th class="">{{__('admin.social_media')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
                {{-- @dd($redirects) --}}
            @foreach($social_media as $post)
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->trans_title}}</span>
                        {!!Builder::BtnGroupRows($post->trans_title, $post->id, [], [
                           'social_media'=>$post->id,
                        ])!!}
                    </td>
                    <td>
                        <span style="display: block;">{{$post->description}}</span>
                        {{-- {!!Builder::BtnGroupRows($post->name, $post->id, [], [
                           'post'=>$post->id,
                        ])!!} --}}
                    </td>
                    <td>
                        {{-- @dd($post->type) --}}
                        <span style="display: block;">{{$post->type->trans_name??null}}</span>
                        {{-- {!!Builder::BtnGroupRows($post->name, $post->id, [], [
                           'post'=>$post->id,
                        ])!!} --}}
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
{{ $social_media->render() }}
