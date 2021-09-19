<div class="col-md-12">
    <table class="table table-condensed table-bordered table-striped" style="font-size: 14px;">
        <thead>
            <tr>
                <th class="">#</th>
                <th class="">{{__('admin.title')}}</th>
                <th class="text-center">{{__('admin.Course Price')}}</th>
                <th class="text-center">{{__('admin.Discount Value')}}</th>
                <th class="text-center">{{__('admin.Exam Price')}}</th>
                <th class="text-center">{{__('admin.Take2 Price')}}</th>
                <th class="text-center">{{__('admin.Total before VAT')}}</th>
                <th class="text-center">{{__('admin.VAT')}}</th>
                <th class="text-center">{{__('admin.Total after VAT')}} ( {{(request()->coin_id_insights==335)?'USD':'SAR'}} )</th>
                {{-- <th class="text-center">{{__('admin.Total after VAT')}} ( {{__('education.SAR')}} )</th> --}}
                {{-- <th class="text-center">{{__('admin.summation')}} ( {{__('education.SAR')}} )</th> --}}
                <th class="text-center">{{__('admin.Sales Conversion')}}</th>
                <th class="text-center">{{__('admin.Count')}}</th>
                <th class="text-center">{{__('admin.Count Conversion')}}</th>
            </tr>
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
            @foreach($totalPerStatus as $result)
            <?php
                $total_before_vat = $result->course_price - $result->discount + $result->exam_price + $result->take2_price;

                $SalesConversion=0; $SalesConversionCount=0;

                // $SummationAll += $result->total;

                if($result->id!=316){
                    $SummationAll += $result->total;
                }

                $CountAll += $result->_count;

                $Index = $loop->iteration;
                if($result->id==63){
                    $NotCompleted = $result->total;
                    $NotCompletedCount = $result->_count;
                }
                else if($result->id==68 || $result->id==376){
                    $SalesConversion = CalculateConversion($result->total, $SalesConversion, $NotCompleted);
                    $SalesConversionCount = CalculateConversion($result->_count, $SalesConversionCount, $NotCompletedCount);
                }

                // ========== For Chart Data ====================
                    // labels:['Not Complete', 'Completed', 'Bakkah Employee', 'Refund', 'Free Seat'],
                    $labels .= "'".GetValueByLang($result->name)."', ";

                    // data:[617594, 181045, 153060, 106519, 105162, 95072],
                    $data .= "'".$result->_count."', ";

                    if($result->id==1){ // Free Seat
                        $data_total .= "'".($total_before_vat*1.15)."', ";
                    }else{
                        $data_total .= "'".$result->total."', ";
                    }
                // ========== For Chart Data ====================
            ?>
            <tr data-id="{{$result->id}}">
                <td>{{$loop->iteration}}</td>
                <td><span class="text-dark">{!!GetValueByLang($result->name)!!}</span></td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->course_price, 2) !!}
                </td>
                <td class="text-center">
                    ({!! NumberFormatWithCommaWithMinus($result->discount, 2) !!})
                </td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->exam_price, 2) !!}
                </td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->take2_price, 2) !!}
                </td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($total_before_vat, 2) !!}
                </td>
                <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->vat_value, 2) !!}
                </td>
                {{-- <td class="text-center">
                    {!! NumberFormatWithCommaWithMinus($result->total_after_vat, 2) !!}
                </td> --}}
                <td class="text-center">
                    @if($result->id!=316)
                        {!! NumberFormatWithCommaWithMinus($result->total, 2) !!}
                    @else
                        ({!! NumberFormatWithCommaWithMinus($result->total, 2) !!})
                    @endif
                </td>
                <td class="text-center"><span class="text-dark">
                    {{$SalesConversion}}
                </span></td>
                <td class="text-center">{!! abs($result->_count) !!}</td>
                <td class="text-center"><span class="text-dark">
                    {{$SalesConversionCount}}
                </span></td>
            </tr>
            @endforeach
            <tr style="font-weight: bold;">
                <td>{{++$Index}}</td>
                <td colspan="7"><span class="text-dark">{{__('admin.Grand Total')}}</span></td>
                <td class="text-center">{!! NumberFormatWithCommaWithMinus($SummationAll, 2) !!}</td>
                <td class="text-center"><span class="text-dark">
                </span></td>
                <td class="text-center">{!! abs($CountAll) !!}</td>
                <td class="text-center"><span class="text-dark">
                </span></td>
            </tr>
        </tbody>
    </table>
    <?php
        // ========== For Chart Data ====================
            $labels = substr($labels, 0, -2);
            $labels = "[".$labels."]";

            $data = substr($data, 0, -2);
            $data = "[".$data."]";

            $data_total = substr($data_total, 0, -2);
            $data_total = "[".$data_total."]";
        // ========== For Chart Data ====================
    ?>
    {{-- {!! $labels !!} --}}
    {{-- {!! $data !!} --}}
    {{-- {!! $data_total !!} --}}
</div>

<script src="https://www.chartjs.org/dist/2.9.4/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}

    /* Chart.js */
    @keyframes chartjs-render-animation{from{opacity:.99}to{opacity:1}}
    .chartjs-render-monitor{animation:chartjs-render-animation 1ms}
    .chartjs-size-monitor,.chartjs-size-monitor-expand,.chartjs-size-monitor-shrink{
        position:absolute;direction:ltr;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1
    }
    .chartjs-size-monitor-expand>div{
        position:absolute;width:1000000px;height:1000000px;left:0;top:0
    }
    .chartjs-size-monitor-shrink>div{
        position:absolute;width:200%;height:200%;left:0;top:0
    }
</style>

{{-- ================================== Bar Chart ============================================= --}}
<div style="width: 50%">
    <div class="chartjs-size-monitor">
        <div class="chartjs-size-monitor-expand">
            <div class=""></div>
        </div>
        <div class="chartjs-size-monitor-shrink">
            <div class=""></div>
        </div>
    </div>
    <canvas id="canvas" class="chartjs-render-monitor"></canvas>
</div>

{{-- ================================== Pie Chart ============================================= --}}
<div style="width: 50%" class="mb-5">
    <canvas id="canvas_2" class="chartjs-render-monitor"></canvas>
</div>

{{-- ================================= Draw Charts ============================================ --}}
<script>
    var ChartData = {
        labels: {!! $labels !!},
        datasets: [{
            label: 'Total After VAT ({!! (request()->coin_id_insights==335)?"USD":"SAR" !!})',
            backgroundColor: [
                window.chartColors.orange,
                window.chartColors.green,
                window.chartColors.purple,
                window.chartColors.red,
                window.chartColors.blue,
                window.chartColors.yellow,
                window.chartColors.red
            ],
            yAxisID: 'y-axis-1',
            data: {!! $data_total !!}
        }, {
            label: 'Count',
            backgroundColor: window.chartColors.grey,
            yAxisID: 'y-axis-2',
            data: {!! $data !!}
        }]
    };

    var ChartOptions = {
        responsive: true,
        title: {
            display: true,
            text: 'Sales Summary'
        },
        tooltips: {
            mode: 'index',
            intersect: true,
            // search in google charts.js format number
            // callbacks: {
            //     label: function(tooltipItem, chart){
            //         var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
            //         var datasetData = chart.datasets[tooltipItem.datasetIndex].yLabel;
            //         console.log(datasetData);
            //         // return datasetLabel + ': $ ' + datasetData.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            //     }
            // }
            // callbacks: {
            //     label: function(tooltipItem, data) {
            //         return tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            //     }
            // }
        },
        scales: {
            yAxes: [{
                type: 'linear',
                display: true,
                position: 'left',
                id: 'y-axis-1',
                }, {
                type: 'linear',
                display: true,
                position: 'right',
                id: 'y-axis-2',
                gridLines: {
                    drawOnChartArea: false
                }
            }],
        }
    };

        // ================================== Bar Chart =============================================
        var ctx = document.getElementById('canvas').getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
            data: ChartData,
            options: ChartOptions,
        });

        // ================================== Pie Chart =============================================
        var ctx = document.getElementById('canvas_2').getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'pie', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
            data: ChartData,
            options: ChartOptions,
        });
</script>
