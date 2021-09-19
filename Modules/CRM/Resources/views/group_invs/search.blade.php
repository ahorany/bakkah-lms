<style>
.form-inline .form-group {
    margin-bottom: 5px;
}
.form-inline .form-group label {
    font-weight: normal !important;
    /* width: 150px; */
}
.form-inline .form-group .form-control {
    width: 100%;
    height: calc(2rem + 2px);
}
.form-inline > div {
    padding-left: 0 !important;
    padding-right: 0 !important;
}
</style>
{{Builder::SetPrefix('crm::admin.')}}
<form id="post-search" class="form-inline" method="get" action="{{route('crm::group_invs.index')}}">
        {!!Builder::Hidden('post_type', $post_type)!!}
        <?php
            $type_id = $cartMaster->type_id??null;
            if(is_null($type_id)){
                if($post_type=='rfq_invoices'){
                    $type_id =  373;
                }elseif($post_type=='group_invs'){
                    $type_id =  372;
                }else{
                    $type_id =  0; //b2b
                }

            }
        ?>
        {!!Builder::Hidden('type_id', $type_id)!!}
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
                            {{-- <div class="col-md-8">
                                <div class="form-group"> --}}

                            {{-- {!! Builder::Select('type_id', 'type_id', $type, request()->type_id??-1, [
                                'col'=>'col-md-6',
                            ]) !!} --}}
                            {!! Builder::Select('organization_id', 'organization_id', $organizations, request()->has('organization_id')?request()->organization_id:null, [
                                'col'=>'col-md-6',
                                'model_title'=>'trans_title'
                            ]) !!}

                            {!! Builder::Input('invoice_number', 'invoice_number', request()->invoice_number??null, ['col'=>'col-md-3']) !!}

                            {!!Builder::Select('owner_user_id', 'owner_user_id', $users, request()->owner_user_id??null, ['col'=>'col-md-3'])!!}

                            {!!Builder::Select('status_id', 'status', $status, request()->status_id??-1, ['col'=>'col-md-3'])!!}

                            {!!Builder::Select('payment_status', 'payment_status', $payment_status, request()->payment_status??-1, ['col'=>'col-md-3'])!!}

                            {!! Builder::Date('follow_up_date_from', 'follow_up_date_from', request()->follow_up_date_from??null, ['col'=>'col-md-3']) !!}
                            {!! Builder::Date('follow_up_date_to', 'follow_up_date_to', request()->follow_up_date_to??null, ['col'=>'col-md-3']) !!}

                            {!! Builder::Input('user_search', 'trainee', request()->user_search??null, [
                                'col'=>'col-md-6',
                                'placeholder'=>__('admin.trainee_search'),
                            ]) !!}

                            {!! Builder::Input('cart_invoice_number', 'cart invoice number', request()->cart_invoice_number??null, ['col'=>'col-md-6']) !!}

                            {{-- </div>
                        </div> --}}
                            <div class="col-md-12"> {{-- class="col-md-6"  --}}
                                {!! Builder::Submit('search', 'search', 'btn-primary mt-2', 'search') !!}
                                {!! Builder::Submit('clear', 'clear', 'btn-default mt-2', 'eraser') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
</form>

<script>
    jQuery(function (){

        jQuery('[name="clear"]').click(function (){
            jQuery('[name="organization_id"], [name="owner_user_id"], [name="status_id"], [name="payment_status"]').val(-1);
            jQuery('[name="invoice_number"], [name="follow_up_date_from"], [name="follow_up_date_to"], [name="user_search"], [name="cart_invoice_number"]').val('');
            return false;
        });
    });
</script>
