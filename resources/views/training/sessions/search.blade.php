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
<form id="cart-search" class="form-inline" method="get" action="{{route('training.sessions.index')}}">
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
                            {{-- <div class="col-md-8">
                                <div class="form-group"> --}}
                                    {!!Builder::Select('course_id', 'course_id', $all_courses, request()->has('course_id')?request()->course_id:null, [
                                        'col'=>'col-md-6',
                                        'model_title'=>'trans_title',
                                    ])!!}

                                    {!!Builder::Select('training_option_id', 'training_option_id', $training_options, request()->has('training_option_id')?request()->training_option_id:null, [
                                        'col'=>'col-md-6',
                                        'model_title'=>'trans_name',
                                    ])!!}

                                    {!! Builder::Date('session_from', 'session_from', request()->session_from??null, ['col'=>'col-md-6']) !!}
                                    {!! Builder::Date('session_to', 'session_to', request()->session_to??null, ['col'=>'col-md-6']) !!}
                                {{-- </div>
                            </div> --}}

                                    {!! Builder::Input('eval_api_search', 'eval_api_search', request()->eval_api_search??null, ['col'=>'col-md-6']) !!}
                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('training.evaluation_api_code')}}</label>
                                            <input type="text" name="eval_api_search"  class="form-control" placeholder="ُEvaluation Collectortoken" value="{{request()->eval_api_search}}" class="form-control">
                                        </div>
                                    </div> --}}

                            {!!Builder::Select('session_status', 'session_status', $session_statuts_compo, request()->has('session_status')?request()->session_status:null, [
                                     'col'=>'col-md-6',
                                     'model_title'=>'trans_name',
                                 ])!!}


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Session Type</label>
                                    <select name="type_id" class="form-control  select2 select2-hidden-accessible">
                                            <option value="">Choose Value</option>
                                            <option value="-1" {{ (request()->type_id == -1) ? 'selected' : '' }}>B2C</option>
                                            <option value="370" {{ (request()->type_id == 370) ? 'selected' : '' }}>B2B</option>
                                    </select>
                                </div>
                            </div>

                            <div style="margin-left:25px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                                {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
{{--                                {!! Builder::checkbox('search', 'search', 'btn-primary', 'search') !!}--}}
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
