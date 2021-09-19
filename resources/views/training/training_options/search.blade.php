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
    .custom-control.custom-checkbox {
        margin-left: 45px;
        margin-top: 5px;
    }
    </style>
    <form id="cart-search" class="form-inline" method="get" action="{{route('training.training_options.index')}}">
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

                                {!!Builder::SelectForSearch('course_id', $all_courses, [
                                    'model_title'=>'trans_title',
                                    'col'=>'col-md-6'
                                ])!!}
                                {!! Builder::SelectForSearch('training_option_id', $delivery_methods, [
                                    'col'=>'col-md-6',
                                ]) !!}
                                {!! Builder::SelectForSearch('lms_id', $lms_options, [
                                    'col'=>'col-md-6',
                                ]) !!}

                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('training.evaluation_api_code')}}</label>
                                        <input type="text" name="eval_api_search"  class="form-control" placeholder="ُEvaluation Collectortoken" value="{{request()->eval_api_search}}" class="form-control">
                                    </div>
                                </div> --}}

                                <div style="margin-left:55px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                    {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                    {!! Builder::Href('clear','post', 'btn-default', 'eraser', route('training.training_options.index')) !!}
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
