<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $ticket->count())!!}

    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th class="">{{__('admin.index')}}</th>
                    <th class="">{{__('admin.title')}}</th>
                    <th class="">{{__('admin.description')}}</th>
                    <th class="">{{__('admin.status')}}</th>
                    <th class="">{{__('admin.priority')}}</th>
                    <th class="">{{__('admin.issue')}}</th>
                    <th class="">{{__('admin.company')}}</th>
                    <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ticket as $post)
                @php
                if($post->status == 445 ){
                $color = 'badge badge-warning';
                }elseif ($post->status == 446) {
                $color = 'badge badge-info';
                }elseif ($post->status == 447) {
                $color = 'badge badge-success';
                }else {
                $color = 'badge badge-danger';
                }
                @endphp

                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->title}}</span>
                        @if( ($post->tracked_by == auth()->id() && $post->updated_status) ||  ($post->updated_status == 0) )
                            {!!Builder::BtnGroupRows($post->title, $post->id, ['Edit']
                            , [
                            'ticket'=>$post->id,
                            ])!!}
                        @endif
                    </td>
                    <td>
                        <span style="display: block;">{{$post->description}}</span>
                    </td>
                    <td>
                        <span class="{{$color}}" style="display: block;">{{$post->states->trans_name??null}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->priorities->trans_name??null}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->issues->trans_name??null}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->companies->trans_name??null}}</span>
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
{{ $ticket->render() }}
