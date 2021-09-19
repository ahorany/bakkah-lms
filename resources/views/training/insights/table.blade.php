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
{{-- {{Builder::SetTrash($trash)}} --}}
{{Builder::SetPostType('insight')}}
{{Builder::SetFolder('carts.insights')}}
{{Builder::SetObject('insight')}}
{{Builder::SetNameSpace('training.')}}
<style>
    .card-columns {
        -webkit-column-count: 2;
        -moz-column-count: 2;
        column-count: 2;
    }
</style>
@if($post_type=='summary' || $post_type=='summary_months')
    <div class="row">
        @include('training.insights.table-parts.total')
    </div>
@else
    <div class="card-columns">

        <div class="col-md-12">
            @include('training.insights.table-parts.total-per-product', [
                'title'=>__('admin.Total Per Payment Status'),
                'results'=>$totalPerStatus,
                'field_name'=>'name',
            ])
        </div>
        <div class="col-md-12">
            @include('training.insights.table-parts.total-per-product', [
                'title'=>__('admin.Total Per Delivery Methods'),
                'results'=>$totalPerDeliveryMethods,
                'field_name'=>'name',
            ])
        </div>

        <div class="col-md-12">
            @include('training.insights.table-parts.total-per-product', [
                'title'=>__('admin.Total Per Categories'),
                'results'=>$totalPerCategories,
                'field_name'=>'name',
            ])
        </div>
        <div class="col-md-12">
            @include('training.insights.table-parts.total-per-product', [
                'title'=>__('admin.Total Per Courses'),
                'results'=>$totalByCourses,
                'field_name'=>'name',
            ])
        </div>
        <div class="col-md-12">
            @include('training.insights.table-parts.total-per-product', [
                'title'=>__('admin.Total Per Country'),
                'results'=>$totalByCountries,
                'field_name'=>'name',
            ])
        </div>

        {{-- <div class="col-md-12">
            @include('training.insights.table-parts.total-per-session', [
                'title'=>__('admin.Total Per Sessions'),
                'results'=>$totalPerSessions,
                'field_name'=>'date_from',
            ])
        </div> --}}

    </div>
@endif
<!-- /.card-body -->
{{-- {{ $byCourses->render() }} --}}

