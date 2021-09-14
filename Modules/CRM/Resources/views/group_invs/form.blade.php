<div class="col-md-12">
    @include(ADMIN.'.Html.alert')
    @include(ADMIN.'.Html.errors')
</div>

{!!Builder::SetNameSpace('')!!}
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetPrefix('crm::admin.')}}

<style>
    body {
        background: #000 !important;
    }
    tr.active {
        background-color: #cfffc2 !important;
    }
    .table td, .table th {
        font-size: 13px !important;
    }
</style>

<div class="row">
    <div class="col-md-9">
        @csrf
        {!!Builder::Hidden('post_type', $post_type)!!}
        <div class="card card-default">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row">

                        {!! Builder::Hidden('master_id', $cartMaster->id??0) !!}
                        {!! Builder::Hidden('rfp_group_id', $cartMaster->rfpGroup->id??0) !!}

                        {!! Builder::Hidden('type_id', 372) !!}

                        <?php $organization_id = $cartMaster->rfpGroup->organization->id??0; ?>

                        {!!Builder::Select('organization_id', 'organization_id', $organizations, $organization_id, ['col'=>'col-md-6', 'model_title'=>'trans_title'])!!}

                        {!! Builder::Hidden('user_id', $cartMaster->user_id??null) !!}

                        @if($organization_id > 0)
                            <?php
                            $href = env('APP_URL');
                            $href .= '/crm/organizations/';
                            $href .= $organization_id;
                            $href .= '/edit?post_type=organizations';
                            ?>
                            <a target="_blank" href="{{$href}}" class="align-self-center btn btn-sm btn-primary" style="margin-top: 15px;"><span>{{__('admin.edit')}}</span></a>
                        @else
                            <a target="_blank" href="{{route('crm::organizations.index')}}" class="align-self-center btn btn-sm btn-primary" style="margin-top: 15px;"><span>{{__('admin.edit')}}</span></a>
                        @endif

                        {!!Builder::Input('reference', 'reference', $cartMaster->reference??null, ['col'=>'col-md-6'])!!}
                        {!!Builder::Input('tax_number', 'tax_number', $cartMaster->tax_number??null, ['col'=>'col-md-6'])!!}

                        {!!Builder::date('follow_up_date', 'follow_up_date', $cartMaster->rfpGroup->follow_up_date??null, ['col'=>'col-md-6'])!!}
                        {!!Builder::date('due_date', 'due_date', $cartMaster->due_date??null, ['col'=>'col-md-6'])!!}

                        {!!Builder::Select('owner_user_id', 'owner_user_id', $users, $cartMaster->rfpGroup->owner_user_id??null, ['col'=>'col-md-6'])!!}

                        {!!Builder::Select('status_id', 'status', $status, $cartMaster->status_id??-1, ['col'=>'col-md-3'])!!}

                        {!!Builder::Select('payment_status', 'payment_status', $payment_status, $cartMaster->payment_status??-1, ['col'=>'col-md-3'])!!}

                        {!!Builder::Number('invoice_amount', 'invoice_amount', $cartMaster->invoice_amount??null, ['col'=>'col-md-6 number'])!!}
                        {!!Builder::Input('invoice_number', 'invoice_number', $cartMaster->invoice_number??null, ['col'=>'col-md-6'])!!}
                        {!!Builder::Input('accounting_sys_invoice', 'accounting_sys_invoice', $cartMaster->accounting_sys_invoice??null, ['col'=>'col-md-6'])!!}
                        {!!Builder::date('payment_sentmail', 'payment_sentmail', $cartMaster->payment_sentmail??null, ['col'=>'col-md-6'])!!}

                        @if(isset($cartMaster))

                            {{-- Start of Add candidate from CRM --}}
                            {!!Builder::Input('crm_invoice_number', 'crm_invoice_number', null, ['col'=>'col-md-3',
                            'placeholder'=>__('admin.invoice_number'),])!!}
                            <button data-id="{{$cartMaster->id??0}}" class="align-self-center btn btn-sm btn-success btn-storeCandidate" style="margin-top: 15px;visibility:visible;">
                                <i class="fa fa-check"></i> {{__('admin.Add Candidate')}}
                            </button>
                            {{-- End of Add candidate from CRM --}}
                        @endif

                        <br>
                        @if(isset($eloquent))
                            <div class="col-md-12 mt-2">
                                <div class="candidates-form">
                                    @include('crm::group_invs.candidates')
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                @include('crm::group_invs.totals')
                            </div>

                            @if(isset($cartMaster))
                                <div class="col-lg-12 col-md-12">
                                    <div class="card card-default">
                                        <div class="card-header"><h5 class="mb-0 float-left mt-2" style="color: #fb4400;"><i class="fas fa-bolt"></i> {{__('education.Actions')}}</h5></div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                {{-- <a target="_blank" href="{{route('training.carts.invoice', $cartMaster->id??0)}}" class="align-self-center btn btn-sm btn-primary"><span>{{__('admin.invoice')}}</span></a> --}}

                                                <a target="_blank" href="{{route('crm::group_invs.exportQuotationToDoc', $cartMaster->id??0)}}" class="btn btn-sm btn-success"><span>{{__('admin.Download Quotation')}}</span></a>

                                                <a target="_blank" href="{{route('crm::group_invs.exportInvoiceToDoc', ['id'=>$cartMaster->id??0 , 'type'=>'invoice'])}}" class="btn btn-sm btn-warning"><span>{{__('admin.Download Invoice')}}</span></a>

                                                <a target="_blank" href="{{route('crm::group_invs.exportInvoiceToDoc', ['id'=>$cartMaster->id??0 , 'type'=>'proforma'])}}" class="btn btn-sm btn-info"><span>{{__('admin.Download Proforma Invoice')}}</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        {!!Builder::BtnGroupForm()!!}
        @if($cartMaster)
            @include('crm::group_invs.notes', ['cartMaster' => $cartMaster])
        @endif
    </div>
</div>
{{-- @section('scripts') --}}
<script>
jQuery(function(){
    jQuery('.btn-storeCandidate').click(function(e){
        e.preventDefault();
        var frm = confirm('Sure To Add?');
        if(frm){
            var id = jQuery(this).data('id');
            var crm_invoice_number = jQuery('input[name="crm_invoice_number"]').val();
            jQuery.ajax({
                type:'get',
                url:"{{route('crm::group_invs.register.storeCandidate')}}",
                data:{
                    master_id:id,
                    crm_invoice_number:crm_invoice_number,
                },
                success:function(data){
                    console.log(data);
                    if(data == 'fail'){
                        alert('Somthing went wrong! Maybe you need to enter the interir invoice number of the candiadate.');
                    }else if(data == 'not_match'){
                        alert('Maybe the candiadate paid before, or his currency USD, need your deep review.');
                    }else{
                        jQuery('.candidates-form').html(data);
                        // alert('Reload the page to see the updates');
                        location.reload();
                    }
                },
                errors:function(e){
                    console.log(e);
                },
            });
        }
        return false;
    });
});
</script>
{{-- @endsection --}}
