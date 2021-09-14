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
    .form-inline > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    select.form-control.select2.select2-hidden-accessible {
        width: 29.7%;
    }
</style>
{{Builder::SetPrefix('crm::admin.')}}
<form id="cart-search" class="form-inline" method="get" action="{{route('crm::b2bs.index')}}">
    <div class="col-md-12">

        <div class="card card-default">
            <div class="card-header">
                <b>{{__('admin.search form')}}</b>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">
                        {!! Builder::Hidden('page', request()->page ?? 1) !!}
                        {!! Builder::Hidden('trash') !!}


                        {!!Builder::Select('organization_id', 'organization_id',$organizations, request()->has('organization_id')?request()->organization_id:null, [
                            'model_title'=>'trans_title',
                        ])!!}

                        {!!Builder::Select('status_id', 'status', $status,  request()->has('status_id')?request()->status_id:null, ['col'=>'col-md-12'])!!}

                        @include('training.carts.components.course_combo')
                        <div style="margin-left:25px;margin-top: 5px;"> {{-- class="col-md-6"  --}}
                            {!! Builder::Submit('search', 'search', 'btn-primary', 'search') !!}
                            {!! Builder::Href('clear','post', 'btn-default', 'eraser', route('crm::b2bs.index')) !!}
                            {{-- {!! Builder::Submit('clear', 'clear', 'btn-default', 'eraser') !!} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</form>
@include('training.carts.components.vue', ['hasJquery'=>false])
