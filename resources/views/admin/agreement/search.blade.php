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
    <form id="post-search" class="form-inline" method="get" action="{{route('admin.agreement.index')}}">
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
                                <div class="col-md-6">
                                    <label style="font-weight: normal; justify-content:end;">Partners</label>
                                    <select style="width: 100%;" class="form-control" name="partners">
                                            <option value="-1">Choose Value</option>
                                        @foreach ($partners as $partner)
                                            <option {{ (request()->partners == $partner->id) ? 'selected' : '' }} value="{{ $partner->id }}">{{ $partner->trans_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 py-4"> {{-- class="col-md-6"  --}}
                                    {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
