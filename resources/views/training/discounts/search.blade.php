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
    .checks{
        margin-left:10px;
        margin-top: 5px;
    }
    .checks div{
        display: inline-block;
    }
    .checks div:not(:first-child){
        margin-left: 30px;
    }
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    </style>
    <form id="cart-search" class="form-inline" method="get" action="{{route('training.discounts.index')}}">
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

                                {!!Builder::SelectForSearch('course_id', $all_courses, [
                                    'model_title'=>'trans_title',
                                    'col'=>'col-md-6'
                                ])!!}

                                {!! Builder::SelectForSearch('training_option_id', $delivery_methods, [
                                    'col'=>'col-md-6',
                                ]) !!}

                                {!! Builder::Input('discount_search', 'discount_search', null, [
                                    'col'=>'col-md-6',
                                    'placeholder'=>__('admin.discount_search_placeholder'),
                                ]) !!}

                                <div class="col-md-3 checks">
                                    {{-- {!!Builder::CheckBox('is_private', null, [
                                        'col'=>'col-md-3',
                                    ])!!} --}}

                                    {!! Builder::SelectForCheckBox('is_private', request()->is_private, [
                                        'col'=>'col-md-3',
                                    ]) !!}
                                    {!! Builder::SelectForCheckBox('is_public', request()->is_public, [
                                        'col'=>'col-md-3',
                                    ]) !!}
                                </div>
                                <div style="margin-left:125px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                    {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                    {{-- {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!} --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </form>
