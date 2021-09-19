@if($results->count()!=0)
<div class="card">
    <div class="card-header">
        <strong>{{$title}}</strong>
    </div>
    <div class="card-body table-responsive p-0">
<table class="table table-condensed table-bordered table-striped table-hover" style="font-size: 14px;">
    <thead>
    <tr>
        <th class="">#</th>
        <th class="" style="width:420px;">{{__('admin.title')}}</th>
        <th class="" style="width:120px;">{{__('admin.summation')}} ( {{(request()->coin_id_insights==335)?'USD':'SAR'}} )</th>
        <th class="" style="width:100px;">{{__('admin.Count')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr data-id="{{$result->id}}">
            <td>{{$loop->iteration}}</td>
            <td>
                <span class="text-info">{!!GetValueByLang($result->short_title)!!}</span>
                <span class="text-muted">( {{$result->date_from}} ) To ( {{$result->date_to}} )</span>
            </td>
            <td class="text-center">
                {!! NumberFormatWithCommaWithMinus($result->total, 2) !!}
            </td>
            <td class="text-center"><span class="text-dark">{{abs($result->_count)}}</span></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
</div>
@endif
