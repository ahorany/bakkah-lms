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
    .custom-control.custom-checkbox {
        margin-left: 160px;
        margin-top: 5px;
    }
    .promo_code_str label{
        display: none;
    }
    input[name="promo_code_str"] {
        width: 77% !important;
    }
</style>
<form id="cart-search" class="form-inline" method="get" action="{{route('training.carts.search')}}">
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
                            {!! Builder::Hidden('post_type', $post_type) !!}
                            {!! Builder::Hidden('trash') !!}

                            @include('training.carts.components.course_combo')

                            {!! Builder::Select('training_option_id', 'training_option_id', $delivery_methods, -1, [
                                'col'=>'col-md-6',
                            ]) !!}

                            {!! Builder::Select('category_id', 'category_id', $categories, request()->category_id??-1, [
                                'col'=>'col-md-6',
                            ]) !!}

                            {!! Builder::Select('payment_status', 'payment_status', $status, request()->payment_status??-1, [
                                'col'=>'col-md-6',
                            ]) !!}

                            {!!Builder::Select('coin_id', 'currency', $currencyConstants, request()->coin_id??-1, ['col'=>'col-md-6'])!!}

                            {!! Builder::Select('country_id', 'country', $countries, request()->course_id??-1, [
                                'col'=>'col-md-6',
                            ]) !!}

                            {!! Builder::SelectForCheckBox('promo_code', request()->promo_code, null, ['col'=>'col-md-6']) !!}

                            {!! Builder::Input('promo_code_str', 'empty', null, ['col'=>'col-md-3', 'placeholder'=>'Promo Code']) !!}
                            {{-- <div class="col-md-6"></div> --}}

                            {{-- {!! Builder::SelectForCheckBox('is_b2b', request()->is_b2b) !!} --}}

                            {!! Builder::Input('user_search', 'trainee', null, [
                                'col'=>'col-md-6',
                                'placeholder'=>__('admin.trainee_search'),
                            ]) !!}

                            {!! Builder::Input('invoice_number', 'invoice number', null, ['col'=>'col-md-6']) !!}

                            {!! Builder::Date('date_from', 'register_from', null, ['col'=>'col-md-6']) !!}
                            {!! Builder::Date('date_to', 'register_to', null, ['col'=>'col-md-6']) !!}

                            {!! Builder::Date('session_from', 'session_from', null, ['col'=>'col-md-6']) !!}
                            {!! Builder::Date('session_to', 'session_to', null, ['col'=>'col-md-6']) !!}

                            {!!Builder::Select('type_id', 'Training Type', $types, null, ['col'=>'col-md-6'])!!}

                            {!! Builder::Input('cart_invoice_number', 'cart invoice number', null, ['col'=>'col-md-6']) !!}

                            {!!Builder::Select('attend_type_id', 'attend_type', $attend_types, request()->attend_type_id??-1, ['col'=>'col-md-6'])!!}
                            {!! Builder::Date('reminder_date', 'reminder_date', null, ['col'=>'col-md-6']) !!}
                        </div>

                        <div style="margin-left:25px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                            {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                            {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</form>

@include('training.carts.components.vue')
