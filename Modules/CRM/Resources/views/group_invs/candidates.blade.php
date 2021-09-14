@if(isset($eloquent))
    <div class="card col-md-12 mt-2 pb-3">
        <div class="card-header">
            <h6 class="mb-0 float-left mt-2" style="color: #fb4400;"><i class="fa fa-users" aria-hidden="true"></i> {{__('education.Candidates')}}</h6>
            <a class="btn btn-sm btn-warning float-right" target="_blank" href="{{route('crm::products-demand.show', $cartMaster->id)}}">Show in CRM</a>
        </div>

        <div class="card-body p-0">
            <table class="table table-responsive table-striped table-hover table-total-info" style="border: 1px solid #dddddd;">
                <tr>
                    <th style="padding: 0 15px;">{{__('education.delete')}}</th>
                    <th style="width: 5%">#</th>
                    <th>CID</th>
                    <th style="min-width: 200px;">{{__('admin.title')}}</th>
                    <th style="width: 15%;">{{__('education.Candidate Name')}}</th>
                    <th>{{__('education.Email')}}</th>
                    <th>{{__('education.Mobile')}}</th>
                    <th>{{__('education.Job Title')}}</th>
                    <th>{{__('education.Country')}}</th>
                    <th>{{__('education.Invoice')}}</th>
                    <th>{{__('education.registered_at')}}</th>
                </tr>

                @foreach ($cartMaster->carts as $cart)
                    <tr data-tr="{{$cart->id}}" {{(($cart->userId->id/2)==0) ? 'class=active' : '' }}>
                        <td style="padding: 0 10px;text-align: center;"><button data-id="{{$cart->id}}" data-mid="{{$cart->master_id}}" class="btn btn-sm btn-danger btn-table btn-delete" style="visibility:visible;padding: 0;">
                            <i class="fa fa-trash"></i> {{__('admin.delete')}}
                        </button></td>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$cart->id}}
                            {{-- <span class=""><a href="{{route('crm::products-demand.show', $cart->master_id)}}" target="_blank">{{$cart->master_id}}</a></span> --}}
                        </td>
                        <td>{{$cart->trainingOption->training_name??null}}</td>
                        <td>{{$cart->userId->trans_name??null}}</td>
                        <td>{{$cart->userId->email??null}}</td>
                        <td>{{$cart->userId->mobile??null}}</td>
                        <td>{{$cart->userId->job_title??null}}</td>
                        <td>{{$cart->userId->country??null}}</td>
                        <td>{{$cart->invoice_number??null}}</td>
                        <td>{{date('d-M-Y', strtotime($cart->registered_at))}}</td>

                        {{-- <td data-id="{{$cart->id}}"><a class="btn btn-sm btn-danger btn-table btn-delete" href="{{route('crm::group_invs.register.delete', ['id'=>$cart->id])}}"><i class="fa fa-trash"></i> {{__('education.delete')}}</a></td> --}}
                    </tr>
                @endforeach

            </table>
        </div>
    </div>
<script>
    jQuery(function(){
        jQuery('.btn-delete').click(function(e){
            e.preventDefault();
            var frm = confirm('Sure To Delete?');
            if(frm){
            var cart_id = jQuery(this).data('id');
            var master_id = jQuery(this).data('mid');
            jQuery.ajax({
                    type:'get',
                    url:"{{route('crm::group_invs.register.delete')}}",
                    data:{
                        cart_id:cart_id,
                        master_id:master_id,
                    },
                    success:function(data){
                        jQuery('tr[data-tr="'+cart_id+'"]').remove();
                        // location.reload();
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
@endif
