<div class="col-md-12">
    <h4>{{__('admin.courses')}}</h4>
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th>
                    <input type="checkbox" id="selectall"/>{{__('admin.check')}}</th>
{{--                        <th <input type="checkbox" checked  name="check_all"> >{{__('admin.check')}}</th>--}}
                    <th class="">{{__('admin.index')}}</th>
                    <th class="">{{__('admin.name')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $post)
                    <tr data-id="{{$post->id}}">
                        <td>
                            @if(in_array($post->id,$discount_courses))
                                <input type="checkbox" checked  name="check_{{$post->id}}">
                            @else
                                <input type="checkbox" name="check_{{$post->id}}">
                            @endif
                        </td>
                        <td>
                            <span class="td-title">{{$post->id}}</span>
                        </td>
                        <td>
                            <span class="td-title">{{$post->trans_title}}</span>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
