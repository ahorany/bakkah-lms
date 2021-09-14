<div class="card">
    <div class="card-header">
        {!!Builder::SetNameSpace('')!!}
        {!!Builder::SetFolder('crm::b2bs')!!}
        {!!Builder::BtnGroupTable()!!}
        {!!Builder::TableAllPosts($count, $b2bs->count())!!}
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th>{{__('Organization')}}</th>
                <th class="">{{__('status')}}</th>
                <th class="">{{__('city_id')}}</th>
                <th class="">{{__('course')}}</th>
                <th class="d-none d-sm-table-cell user-td">{{__('admin.user')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($b2bs as $b2b)
                <tr data-id="{{$b2b->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$b2b->organization->en_title}}</span>
                        {!!Builder::BtnGroupRows($b2b->en_title, $b2b->id, [], [
                           'b2b'=>$b2b->id,
                        ])!!}
                    </td>
                    <td>
                        <span style="display: block;">{{$b2b->status->post_type}}</span>
                    </td>
                    <td>
                        <span style="display: block;">Cairo</span>
                    </td>
                    <td>
                        <span class="td-title">{{$b2b->course ?$b2b->course->en_title :''}}</span>
                    </td>

                    <td class="d-none d-sm-table-cell">
                      <span class="author">
                        {!!$b2b->published_at!!}
                      </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->

