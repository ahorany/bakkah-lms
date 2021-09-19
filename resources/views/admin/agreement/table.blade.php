<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $agreement->count())!!}

    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed text-center">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.partners')}}</th>
                <th class="">{{__('admin.signing_date')}}</th>
                <th class="">{{__('admin.expired_date')}}</th>
                <th class="">{{__('admin.Number of Days')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
                {{-- @dd($agreement) --}}
            @foreach($agreement as $post)

                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->partner->trans_name}}</span>
                        {!!Builder::BtnGroupRows($post->partner_id, $post->id, [], [
                           'agreement'=>$post->id,
                        ])!!}
                    </td>
                    <td>
                        <span style="display: block;">{{$post->signing_date}}</span>
                    </td>
                    <td>
                        @php
                            $t = 'class="badge badge-success"';
                            $f = 'class="badge badge-danger"';
                        @endphp
                        <span style="display: block;" {!! ($post->expired_date > $now_date) ? $t : $f !!}>{{($post->expired_date)}}</span>
                    </td>
                    <td>
                        {{-- @php
                            $t = '<i style="color: #0fc10f; font-size: 20px;" class="fas fa-check"></i>';
                            $f = '<i style="color: #f31f1f; font-size: 20px;" class="fas fa-times"></i>';
                        @endphp --}}
                         @php
                        $datetime1 = strtotime($now_date); // convert to timestamps
                        $datetime2 = strtotime($post->expired_date); // convert to timestamps
                        $days = (int)(($datetime2 - $datetime1)/86400); // will give the difference in days , 86400 is the timestamp difference of a day
                         @endphp

                        <span style="display: block;">{!! $days !!}</span>
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
{{ $agreement->render() }}
