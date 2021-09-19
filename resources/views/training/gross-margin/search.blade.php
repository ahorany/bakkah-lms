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
    /* select.form-control.select2.select2-hidden-accessible {
        width: 29.7%;
    } */
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    </style>
    <form id="cart-search" class="form-inline" method="get" action="{{route('training.gross-margin.index')}}">
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
                                {!! Builder::Hidden('post_type', request()->post_type??null) !!}
                                {!! Builder::Hidden('trash') !!}

                                        {!!Builder::Select('course_id', 'course_id', $all_courses, request()->has('course_id')?request()->course_id:null, [
                                            'col'=>'col-md-6',
                                            'model_title'=>'trans_title',
                                        ])!!}

                                        {!!Builder::Select('is_confirmed', 'is_confirmed', $confirms, request()->has('is_confirmed')?request()->is_confirmed:null, [
                                            'col'=>'col-md-6',
                                            'model_title'=>'trans_name',
                                        ])!!}

                                        {!! Builder::Date('session_from', 'session_from', request()->session_from??null, ['col'=>'col-md-6']) !!}
                                        {!! Builder::Date('session_to', 'session_to', request()->session_to??null, ['col'=>'col-md-6']) !!}
                                        
                                <div style="margin-left:25px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                    {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                    {{-- {!! Builder::checkbox('search', 'search', 'btn-primary', 'search') !!}--}}
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
