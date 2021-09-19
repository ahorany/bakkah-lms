<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label {
        font-weight: normal !important;
        width: 150px;
    }
    .form-inline .form-group .form-control {
        width: 60%;
        height: calc(2rem + 2px);
    }
    .inline-list{
        margin: 10px 0;
    }
    .inline-list li {
        display: inline-block;
    }
    .inline-list li:not(:first-child){
        margin-left: 40px;
    }
    .custom-control.custom-checkbox label {
        width: auto;
        justify-content: end;
    }
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

</style>
<form id="cart-search" class="form-inline" method="get" action="{{route('training.carts.insightsSearch')}}">
    <div class="row">
        <div class="col-md-12">

            <div class="card card-default">
                <div class="card-header">
                    <b>{{__('admin.search form')}}</b>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            {!! Builder::Hidden('page', request()->page??1) !!}
                            {!! Builder::Hidden('with_months', request()->with_months??'no') !!}
                            {!! Builder::Hidden('post_type', request()->post_type??'insight') !!}
                            {!! Builder::Hidden('trash') !!}

                            @include('training.carts.components.course_combo')

                            {!! Builder::Select('training_option_id', 'training_option_id', $delivery_methods, request()->training_option_id??-1, [
                                'col'=>'col-md-6',
                            ]) !!}

                            {!! Builder::Select('category_id', 'category_id', $categories, request()->category_id??-1, [
                                'col'=>'col-md-6',
                            ]) !!}

                            {!! Builder::Select('payment_status', 'payment_status', $status, request()->payment_status??-1, [
                                'col'=>'col-md-6',
                            ]) !!}

                            {!! Builder::Select('country_id', 'country', $countries, request()->country_id??-1, [
                                'col'=>'col-md-6',
                            ]) !!}

                            {!!Builder::Select('coin_id_insights', 'currency', $currencyConstants, request()->coin_id??334, ['col'=>'col-md-6'])!!}

                            {{-- {!! Builder::Select('vat_filter', 'vat_filter', $vat_filters, request()->vat_filter??-1, [
                                'col'=>'col-md-6',
                            ]) !!} --}}

                            <div class="col-md-6">
                                {{-- <div class="form-group">
                                    <label>Products Filter</label>
                                    <ul class="inline-list list-unstyled">
                                    @foreach($product_filters as $product_filter)
                                    <li>
                                        {!!Builder::CheckBox($product_filter->slug, $product_filter->trans_name)!!}
                                    </li>
                                    @endforeach
                                    </ul>
                                </div> --}}
                            </div>

                            {{-- <div class="col-md-6"></div> --}}

                            {!! Builder::Date('register_from', 'register_from', request()->register_from??null, ['col'=>'col-md-6']) !!}
                            {!! Builder::Date('register_to', 'register_to', request()->register_to??null, ['col'=>'col-md-6']) !!}

                            {!! Builder::Date('session_from', 'session_from', request()->session_from??null, ['col'=>'col-md-6']) !!}
                            {!! Builder::Date('session_to', 'session_to', request()->session_to??null, ['col'=>'col-md-6']) !!}

                            {{--<div style="margin-left:25px;margin-top: 5px;">
                                {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                <a class="btn btn-default btn-sm" href="{{route('training.carts.insights')}}"> <i class="fa fa-eraser"></i> {{__('admin.clear')}}</a>
                            </div>--}}
                            <div class="col-md-6" style="margin-left:25px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</form>

@include('training.carts.components.vue')
