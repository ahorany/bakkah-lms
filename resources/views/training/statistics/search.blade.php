<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label {
        font-weight: normal !important;
        width: 190px;
    }
    .form-inline .form-group .form-control {
        width: 60%;
        height: calc(2rem + 2px);
    }
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    </style>
    {{-- form-group d-flex align-items-center --}}
<form id="statistics-search" method="get" action="{{route('training.carts.statistics')}}">
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
                            {!! Builder::Hidden('trash') !!}

                            {!! Builder::Input('user_search', 'trainee', request()->user_search??null, [
                                'col'=>'col-md-6',
                                'placeholder'=>__('admin.trainee_search'),
                            ]) !!}

                            {!! Builder::Date('date_from', 'register_from', request()->date_from??null, ['col'=>'col-md-2']) !!}
                            {!! Builder::Date('date_to', 'register_to', request()->date_to??null, ['col'=>'col-md-2']) !!}

                            <div class="col col-md-2" style="padding-top: 36px;"> {{-- class="col-md-6" margin-left:25px;margin-top: 5px; --}}
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
