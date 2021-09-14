<style>
    .form-inline .form-group {
        margin-bottom: 5px;
    }
    .form-inline .form-group label {
        font-weight: normal !important;
        width: 150px;
    }
    .form-inline .form-group .form-control {
        width: 100%;
        height: calc(2rem + 2px);
    }
    input.form-control {
        width: 630px !important;
    }
    .custom-control.custom-checkbox {
        padding-left: 100px;
    }
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    </style>
    <form id="post-search" class="form-inline" method="get" action="{{route('admin.ticket.index')}}">
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

                                <div class="col-md-12 col-12 mb-4">
                                    <input type="text" name="title_search" class="form-control w-100" placeholder="Input title to search" style="width: 100% !important;" value="{{request()->title_search}}">
                                </div>

                                        <div class="col-md-1 col-3 py-3">
                                            <label for="">Status: </label>
                                        </div>
                                        <div class="col-md-3 col-9">
                                            <select class="form-control m-2" name="status">
                                                <option value="-1">Choose Value</option>
                                                @foreach ($status as $status)
                                                    <option {{ (request()->status == $status->id) ? 'selected' : '' }} value="{{ $status->id }}">{{ $status->trans_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-3 py-3">
                                            <label for="">Priority: </label>
                                        </div>
                                        <div class="col-md-3 col-9">
                                            <select class="form-control m-2" name="priority">
                                                <option value="-1">Choose Value</option>
                                                @foreach ($priorities as $priority)
                                                    <option {{ (request()->priority == $priority->id) ? 'selected' : '' }} value="{{ $priority->id }}">{{ $priority->trans_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-3 py-3">
                                            <label for="">Company: </label>
                                        </div>
                                        <div class="col-md-3 col-9">
                                            <select class="form-control m-2" name="company">
                                                <option value="-1">Choose Value</option>
                                                @foreach ($companies as $company)
                                                    <option {{ (request()->company == $company->id) ? 'selected' : '' }} value="{{ $company->id }}">{{ $company->trans_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                <div class="col-md-12 col-12 mt-4">
                                    {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
    </form>
