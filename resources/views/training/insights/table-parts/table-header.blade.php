<tr>
    <th class="text-center align-middle">#</th>
    {{-- @if(request()->with_months == 'yes') <th class="text-center align-middle">{{__('admin.year-month')}}</th>  @endif --}}
    <th class="text-center align-middle">{{__('admin.title')}}</th>
    <th class="text-center align-middle">{{__('admin.Course Price')}}</th>
    {{-- <th class="text-center align-middle">{{__('admin.Discount Value')}}</th> --}}
    <th class="text-center align-middle">{{__('admin.Exam Price')}}</th>
    <th class="text-center align-middle">{{__('admin.Pract. Exam Price')}}</th>
    <th class="text-center align-middle">{{__('admin.Take2 Price')}}</th>
    <th class="text-center align-middle">{{__('admin.exam_simulation_price')}}</th>
    <th class="text-center align-middle">{{__('admin.Book Price')}}</th>
    <th class="text-center align-middle">{{__('admin.Total before VAT')}}</th>
    <th class="text-center align-middle">{{__('admin.VAT')}}</th>
    {{-- @if(auth()->check())
        @if(auth()->user()->id==2)
        <th class="text-center align-middle">Total after VAT ( {{(request()->coin_id_insights==335)?'USD':'SAR'}} )</th>
        @endif
    @endif --}}
    {{-- <th class="text-center align-middle">{{__('admin.Total after VAT')}} ( {{(request()->coin_id_insights==335)?'USD':'SAR'}} )</th> --}}
    <th class="text-center align-middle">Paid Amount <br>( {{(request()->coin_id_insights==335)?'USD':'SAR'}} )</th>
    {{-- <th class="text-center align-middle">{{__('admin.Total after VAT')}} ( {{__('education.SAR')}} )</th> --}}
    {{-- <th class="text-center align-middle">{{__('admin.summation')}} ( {{__('education.SAR')}} )</th> --}}
    <th class="text-center align-middle">{{__('admin.Sales Conversion')}}</th>
    <th class="text-center align-middle">{{__('admin.Count')}}</th>
    <th class="text-center align-middle">{{__('admin.Count Conversion')}}</th>
    @if(auth()->check())
        @if(auth()->user()->id==2)
            <th class="text-center align-middle">Diff</th>
        @endif
    @endif
</tr>
