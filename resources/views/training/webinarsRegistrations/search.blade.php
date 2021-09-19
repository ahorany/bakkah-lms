<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label, label {
        font-weight: normal !important;
        width: 110px;
    }
    .form-inline .form-group .form-control {
        width: 70%;
        height: calc(2rem + 2px);
    }
    .form-inline .row{
        width: 100% !important;
    }
    .card-body {
        padding: 15px;
    }
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .send{
        height: 86px;;
    }

</style>

<form id="webinar-search" class="" method="get" action="{{route('training.webinarsRegistrations.index')}}">
    <div class="row">
        <div class="col-md-12">

            <div class="card card-default">
                <div class="card-header">
                    <b>{{__('admin.search form')}}</b>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            {!! Builder::Hidden('page', request()->page??1) !!}
                            {!! Builder::Hidden('trash') !!}

                            {!! Builder::Select('webinar_id', 'webinar_title', $all_webinars, request()->webinar_id??-1, [
                                'col'=>'col-md-4',
                                'model_title'=>'trans_title',
                            ]) !!}

                            {{-- {!! Builder::Input('user_search', 'user', null, [
                                'col'=>'col-md-4',
                                'placeholder'=>__('admin.User Data'),
                            ]) !!} --}}

                            {{-- <label>{{__('admin.User Data')}}</label>
                            <input type="text" name="user_data_search"  class="form-control col-md-3" placeholder="User name, email, mobile" value="{{request()->user_data_search}}" class="form-control"> --}}

                            {!! Builder::Input('user_search', 'user', request()->user_search??null, [
                                'col'=>'col-md-3',
                                'placeholder'=>__('admin.User Data'),
                            ]) !!}

                            <div class="col-md-3 send">
                                    <label style="font-weight: normal;">Status</label>
                                    <select style="width: 100%;" class="form-control" name="status">
                                        <option value="-1">Choose Value</option>
                                        <option {{ (request()->status == 'sending') ? 'selected' : '' }} value="sending">Sending</option>
                                        <option {{ (request()->status == 'not_sending') ? 'selected' : '' }} value="not_sending">Not Sending</option>
                                    </select>
                            </div>

                            <div class="col-md-2 mt-3"> {{-- class="col-md-6"  --}}
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
