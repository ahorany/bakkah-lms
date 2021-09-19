{{-- <div class="card mb-4">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0"><i class="far fa-paper-plane" aria-hidden="true"></i> Send Emails</h6>

        </div>
    </div>
    <div class="card-body p-0"> --}}

        {{-- <div class="card mb-1"> --}}
            <button :disabled="sendMessages" @click.prevent="send_invoice()" class="btn btn-success btn-sm my-2 btn-block" style="font-family: 'Lato-Regular'; font-size: 13px;">
                <span v-if="!sendMessages"><li class="fa fa-paper-plane"></li> Send Invoice & Payment Receipt</span>
                <span v-else><li class="fa fa-spinner fa-spin"></li> Wait while sending email...</span>
            </button>
            <template v-if="cartMaster.invoice_sent_date">
                <small class="text-success d-block pb-2">Invoice & Payment Receipt were sent at @{{cartMaster.invoice_sent_date}}</small>
            </template>
        {{-- </div> --}}

        {{-- <a target="_blank" href="{{route('training.carts.invoice', $cartMaster->id??0)}}" class="align-self-center btn btn-sm btn-primary"><span>{{__('admin.invoice')}}</span></a> --}}
    {{-- </div> --}}
{{-- </div> --}}
