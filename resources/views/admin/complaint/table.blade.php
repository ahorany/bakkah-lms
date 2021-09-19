<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $complaint->count())!!}
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.partners')}}</th>
                <th class="">{{__('admin.submission_date')}}</th>
                <th class="">{{__('admin.department')}}</th>
                <th class="">{{__('admin.status')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
                @foreach($complaint as $post)
                @php
                if($post->states->id == 477 ){
                $color = 'badge badge-warning';
                }elseif ($post->states->id == 476) {
                $color = 'badge badge-info';
                }elseif ($post->states->id == 475) {
                $color = 'badge badge-success';
                }
                @endphp
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->partner->trans_name??null}}</span>
                        {!!Builder::BtnGroupRows($post->partner_id, $post->id, [], [
                           'complaint'=>$post->id,
                        ])!!}
                    </td>
                    <td>
                        <span style="display: block;">{{$post->submission_date}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->departments->trans_name}}</span>
                    </td>
                    <td>
                        <span class="{{$color??''}}" style="display: block;">{{$post->states->trans_name}}</span>
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
{{ $complaint->render() }}
