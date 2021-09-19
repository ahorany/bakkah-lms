<?php use App\Helpers\Lang; ?>
@if(count($results)!=0)
{{-- @if($results->count()!=0) --}}
<div class="card">
    <div class="card-header">
        <strong>{{$title}}</strong>
    </div>
    <div class="card-body table-responsive p-0">
<table class="table table-condensed table-bordered table-striped table-hover" style="font-size: 14px;">
    <thead>
    <tr>
        <th class="">#</th>
        <th class="" style="width:400px;">{{__('admin.title')}}</th>
        <th class="" style="width:120px;">{{__('admin.summation')}} ( {{(request()->coin_id_insights==335)?'USD':'SAR'}} )</th>
        <th class="" style="width:120px;">{{__('admin.Count')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
        <tr data-id="{{$result->id}}">
            <td>{{$loop->iteration}}</td>
            {{-- {!!GetValueByLang($result->_title)!!} --}}
            <td><span class="text-dark">{!!Lang::TransTitle($result->$field_name)!!}</span></td>
            <td class="text-center">
                @if($result->id==332)  {{-- // Free Seat --}}
                        0.00
                    @elseif($result->id==316)  {{-- // Refund --}}
                        ({!! NumberFormatWithCommaWithMinus($result->paid_out, 2) !!})
                    @elseif($result->id==317 || $result->id==63)  {{-- // PO or Not Completed--}}
                        {!! NumberFormatWithCommaWithMinus($result->total_after_vat, 2) !!}
                    @else
                        {{-- {!! NumberFormatWithCommaWithMinus($result->paid_in, 2) !!} --}}
                        {!! NumberFormatWithCommaWithMinus($result->total_after_vat, 2) !!}
                    @endif
            </td>
            <td class="text-center"><span class="text-dark">{{abs($result->_count)}}</span></td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>
</div>
@endif
