<div class="card">
    <div class="card-header">
        {!!Builder::TableAllPosts($count, $complaints->count())!!}
    </div>
<style>
    .false_color {
        background: #ffc107;
        color: #212529;
        width: 70%;
        border-radius: 5px;
        padding: 3px 0;
        border: none;
        font-weight: 700;
        font-size:13px;
        display: block;
        text-align: center;
    }
    .true_color {
        background: #28a745;
        color: #fff;
        width: 70%;
        border-radius: 5px;
        padding: 3px 0;
        border: none;
        font-weight: 700;
        font-size:13px;
        display: block;
        text-align: center;
    }
    .true_color:hover{
        color: #fff;
    }
    .false_color:hover{
        color: #212529;
    }
    table.complaints{
        text-align: center;
    }
</style>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-condensed complaints">
            <thead>
                <tr>
                    <th class="">{{__('admin.index')}}</th>
                    <th class="">{{__('admin.courses')}}</th>
                    <th class="">{{__('admin.complaints')}}</th>
                    <th class="">{{__('admin.status')}}</th>
                    <th class="">{{__('admin.date')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($complaints as $answer)
                <tr data-id="{{$answer->id}}">
                    <td class="py-3">
                        <span style="display: block;">{{$loop->iteration}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$answer->products->trans_title??null}}</span>
                    </td>
                    <td>
                        <span style="display: block;">{{$answer->profile_answer_text??'There is no reason.'}}</span>
                    </td>
                    <td>
                        @if ($answer->status == 0)
                        <a href="{{route('admin.get_complaints',$answer->id)}}" name="status" class="{{($answer->status == 1) ? 'true_color' : 'false_color'}}">{{($answer->status == 1) ? "Done" : "In Progress"}}</a>
                        @endif
                        @if ($answer->status == 1)
                        <span class="{{($answer->status == 1) ? 'true_color' : 'false_color'}}">{{($answer->status == 1) ? "Done" : "In Progress"}}</span>
                        @endif
                    </td>
                    <td>
                        <span style="display: block;">{{$answer->created_at??null}}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{ $complaints->render() }}
