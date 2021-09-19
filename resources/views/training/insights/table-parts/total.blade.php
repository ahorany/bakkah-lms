<?php use App\Helpers\Lang; ?>
<div class="col-md-12">
    <table class="table table-condensed table-bordered table-striped" style="font-size: 14px;">
        <thead>
            @if(request()->with_months == 'no')
                @include('training.insights.table-parts.table-header')
            @endif
        </thead>
        <tbody>
            <?php
                // ========== For Chart Data ====================
                    $labels = '';
                    $data = '';
                    $data_total = '';
                // ========== For Chart Data ====================

                $NotCompleted=0; $NotCompletedCount=0;
                $SummationAll=0; $CountAll=0; $Index=0;;
                function CalculateConversion($total, $SalesConversion, $NotCompleted){
                    $denominator = $total + $NotCompleted;
                    $SalesConversion = $total / $denominator;
                    $SalesConversion *= 100;
                    $SalesConversion = round($SalesConversion, 2);
                    $SalesConversion .= ' %';
                    return $SalesConversion;
                }
            ?>
            {{-- splice() --}}

            @if(request()->with_months == 'yes')
                <?php
                    $month_year= '';
                    $month_year_old = '';
                ?>
            @endif

            @foreach($totalPerStatus as $result)
            <?php
                $course_price = $result->course_price - $result->discount;
                $total_before_vat = $course_price + $result->exam_price + $result->take2_price + $result->exam_simulation_price + $result->pract_exam_price + $result->book_price;

                $SalesConversion=0; $SalesConversionCount=0;

                // if($result->id!=316 && $result->id!=332){ // Refund or Free
                if($result->id==68 || $result->id==315){
                    // $SummationAll += $result->paid_in;
                    $SummationAll += $result->total_after_vat;
                }elseif ($result->id==63 || $result->id==317) {
                    $SummationAll += $result->total_after_vat;
                }

                $CountAll += $result->_count;

                $Index = $loop->iteration;
                if($result->id==63){
                    $NotCompleted = $result->total_after_vat;
                    $NotCompletedCount = $result->_count;
                }
                elseif($result->id==68){
                    $SalesConversion = CalculateConversion($result->total_after_vat, $SalesConversion, $NotCompleted);
                    // $SalesConversion = CalculateConversion($result->paid_in, $SalesConversion, $NotCompleted);
                    $SalesConversionCount = CalculateConversion($result->_count, $SalesConversionCount, $NotCompletedCount);
                }

                // ========== For Chart Data ====================
                    // labels:['Not Complete', 'Completed', 'Bakkah Employee', 'Refund', 'Free Seat'],
                    // $labels .= "'".GetValueByLang($result->name)."', ";

                    // data:[617594, 181045, 153060, 106519, 105162, 95072],
                    // $data .= "'".$result->_count."', ";

                    // if($result->payment_status==332){ // Free Seat
                    //     $data_total .= "'".($total_before_vat*1.15)."', ";
                    // }else{
                    //     $data_total .= "'".$result->total."', ";
                    // }

                    // if($result->id==1){ // Free Seat
                    //     $data_total .= "'".($total_before_vat*1.15)."', ";
                    // }else{
                    //     $data_total .= "'".$result->total."', ";
                    // }
                // ========== For Chart Data ====================
            ?>
            {{-- <tr data-id="{{$result->id}}"> --}}
            @if(request()->with_months == 'yes')
                <?php
                    $month_year_new = $result->reg_date;
                    if($loop->iteration==1){
                        $month_year_old = $month_year_new;
                        ?>
                        <tr><td colspan="20" style="background-color: white;"><h5 class="pt-2 text-red">{{date('M Y', strtotime($result->reg_date))}}</h5></td></tr>
                        @include('training.insights.table-parts.table-header')
                        <?php
                    }
                ?>
                @if($month_year_new != $month_year_old)
                    <tr><td colspan="20" style="background-color: white;"><h5 class="pt-2 text-red">{{date('M Y', strtotime($result->reg_date))}}</h5></td></tr>
                    @include('training.insights.table-parts.table-header')
                @endif
                <?php
                    if($loop->iteration>1){
                        $month_year_old = $result->reg_date;
                    }
                ?>
            @endif
            <tr data-id="{{$result->id}}">
                <td>{{$loop->iteration}}</td>
                {{-- @if(request()->with_months == 'yes') <td>{{$result->reg_date}}</td> @endif --}}
                {{-- {!!GetValueByLang($result->name)!!} --}}
                <td><span class="text-dark">{!!Lang::TransTitle($result->name)!!}</span></td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($course_price, 2) !!}
                </td>
                {{-- <td class="text-center">
                    ({!! NumberFormatWithCommaWithMinus($result->discount, 2) !!})
                </td> --}}
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->exam_price, 2) !!}
                </td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->pract_exam_price, 2) !!}
                </td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->take2_price, 2) !!}
                </td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->exam_simulation_price, 2) !!}
                </td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->book_price, 2) !!}
                </td>
                <td class="text-center">
                    @if($result->id==332)
                        0.00
                    @else
                        {!! NumberFormatWithCommaWithMinus($total_before_vat, 2) !!}
                    @endif
                </td>
                <td class="text-center">
                    @if($result->id==332)
                        0.00
                    @else
                        {!! NumberFormatWithCommaWithMinus($result->vat_value, 2) !!}
                    @endif
                </td>
                {{-- <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->total_after_vat, 2) !!}
                </td> --}}
                {{-- @if(auth()->check())
                    @if(auth()->user()->id==2)
                        <td class="text-center">
                            {!! NumberFormatWithCommaWithMinus($result->total_after_vat, 2) !!}
                        </td>
                    @endif
                @endif --}}
                <td class="text-center">
                    @if($result->id==332)  {{-- // Free Seat --}}
                        0.00
                    @elseif($result->id==316)  {{-- // Refund --}}
                        ({!! NumberFormatWithCommaWithMinus($result->paid_out, 2) !!})
                    @elseif($result->id==317 || $result->id==63)  {{-- // PO or Not Completed--}}
                        {!! NumberFormatWithCommaWithMinus($result->total_after_vat, 2) !!}
                    @else
                        {!! NumberFormatWithCommaWithMinus($result->total_after_vat, 2) !!}
                        {{-- {!! NumberFormatWithCommaWithMinus($result->paid_in, 2) !!} --}}
                    @endif
                </td>

                <td class="text-center"><span class="text-dark">
                    {{$SalesConversion}}
                </span></td>
                <td class="text-center">{!! abs($result->_count) !!}</td>
                <td class="text-center"><span class="text-dark">
                    {{$SalesConversionCount}}
                </span></td>

                @if(auth()->check())
                    @if(auth()->user()->id==2)
                        <td class="text-center">
                            {!! NumberFormatWithCommaWithMinus($result->diff, 2) !!}
                        </td>
                    @endif
                @endif
            </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td>{{++$Index}}</td>
                <td colspan="9"><span class="text-dark">{{__('admin.Grand Total')}}</span></td>
                <td class="text-center">{!! NumberFormatWithCommaWithMinus($SummationAll, 2) !!}</td>
                <td class="text-center"><span class="text-dark">
                </span></td>
                <td class="text-center">{!! abs($CountAll) !!}</td>
                <td class="text-center"></td>
                @if(auth()->check())
                    @if(auth()->user()->id==2)
                    <td class="text-center"></td>
                    @endif
                @endif
            </tr>
        </tbody>
    </table>
</div>
