
<div class="card">
    <div class="card-header">
        {!!Builder::BtnGroupTable()!!}

        <div class="float-right d-inline-flex align-items-center justify-content-between">
            {!!Builder::TableAllPosts($count, $projects->count())!!}
        </div>

    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed">
            <thead>
            <tr>
                <th class="">{{__('admin.index')}}</th>
                <th class="">{{__('admin.name')}}</th>
                <th class="">{{__('training.time_line')}}</th>
                <th class="">{{__('training.budget')}}</th>
            </tr>
            </thead>
            <tbody>
         
            @foreach($projects as $post)
                <tr data-id="{{$post->id}}">
                    <td>
                        <span class="td-title">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$post->title??null}}</span>
                        {!! Builder::BtnGroupRows($post->title, $post->id, [], [
                        'post'=>$post->id,
                        ]) !!}
                    </td>
                    <td>
                        <span class="light">
                            {{$post->time_line}}
                        </span>
                    </td>
                  
                    <td>
                        <span class="light">
                            {{$post->budget}}  {{ \App\Helpers\Lang::TransTitle($post->name) }}
                        </span>
                    </td>
                
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<?php
    // $array = Builder::get_appends(request()->all());
?>
{{-- {{ $projects->appends($array)->links() }} --}}
