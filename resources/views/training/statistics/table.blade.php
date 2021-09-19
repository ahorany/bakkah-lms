<style>
    .price-card .title {
        width: 150px;
    }
    span.value {
        font-weight: bold;
        font-size: 12px;
        color: #666;
    }
    .table-total-info {
        font-size: 14px;
    }
    .table-total-info td {
        padding-left: 1rem !important;
    }
    .paid-value {
        font-size: 13px; font-weight: normal;
    }
</style>
{{Builder::SetTrash($trash)}}
{{Builder::SetPostType('statistic')}}
{{Builder::SetFolder('statistics')}}
{{Builder::SetObject('statistic')}}
{{Builder::SetNameSpace('training.')}}
<div class="card">
    <div class="card-header">
        {{-- {!!Builder::BtnGroupTable(false)!!} --}}

        <div class="float-right d-inline-flex align-items-center justify-content-between">
            {!!Builder::TableAllPosts($statistics__all->count(), $statistics->count())!!}
            <a class="btn btn-success float-right mx-3" id="export-btn" href="{{route('training.carts.statistics.export')}}">Export Results</a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-condensed" style="font-size: 14px;">
            <thead>
            <tr>
                <th class="">CID</th>
                <th class="">Email</th>
                <th class="">Mobile</th>
                <th class="">Name</th>
                <th class="">Course Name</th>
                <th class="">Registered At</th>
            </tr>
            </thead>
            <tbody>
            @foreach($statistics as $statistic)
                <tr data-id="{{$statistic->id}}">
                    <td>
                        <span class="td-title">{{$statistic->id}}</span>
                    </td>
                    <td>
                        <span class="td-title">{{$statistic->email}}</span>
                    </td>
                    <td>
                        <span class="td-title">{{$statistic->mobile}}</span>
                    </td>
                    <td>
                        <span class="td-title">{{ App\Helpers\Lang::TransTitle($statistic->name)}}</span>
                    </td>
                    <td>
                        <span class="td-title">{{App\Helpers\Lang::TransTitle($statistic->title)}}</span>
                    </td>
                    <td>
                        <span class="td-title">{{date("d-m-Y", strtotime($statistic->registered_at))}}</span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /.card-body -->
{{-- {{ $statistics->render() }} --}}
