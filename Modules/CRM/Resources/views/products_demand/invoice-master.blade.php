<div class="card card-info">
    <div class="card-header pt-3 pb-3">
        <h6 class="mb-0 float-left"><i class="far fa-file-alt" aria-hidden="true"></i> Invoice Info</h6>
        <h6 class="mb-0 float-right" v-text="cartMaster.registered_at"></h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-striped table-hover table-total-info" :key="cartMaster.id">
            <tr style="border-top: 2px solid #fb4400;">
                <td>{{__('admin.invoice number')}}</td>
                <td>
                    {{-- <input v-if="moode" type="number" class="form-control" v-model="cartMaster_edit.invoice_number"> --}}
                    <span class="text-secondary" v-text="cartMaster_edit.invoice_number"></span>
                </td>
            </tr>
            <tr>
                <td class="title">{{__('admin.sub-total')}}</td>
                <td class="value text-secondary" v-text="cartMaster_edit.total"></td>
            </tr>
            <tr>
                <td class="title">{{__('admin.vat')}}(<span style="color:#dc3545;" v-text="cartMaster.vat"></span><span style="color:#dc3545;">%</span>)</td>
                <td class="value text-secondary" v-text="cartMaster_edit.vat_value"></td>
            </tr>
            <tr>
                {{-- font-weight-bold --}}
                <td class="title">{{__('admin.total_after_vat')}}</td>
                <td class="value text-primary" v-text="cartMaster_edit.total_after_vat"></td>
            </tr>
            <tr>
                <td class="title text-primary">Paid in Amount</td>
                <td class="value text-primary pl-1">
                    <template v-if="cartMaster_edit.payment_paid_in">
                    {{-- <template v-if="cartMaster.payment"> --}}
                        <span class="paid-value badge" style="font-size: 16px;">
                            <strong v-text="parseFloat(cartMaster_edit.payment_paid_in).toFixed(2)"></strong>
                            <strong><small v-text="converJson(cartMaster.coin.name).en"></small></strong>
                        </span><br>
                    </template>
                </td>
            </tr>
            <tr>
                <td class="title text-danger">Paid out Amount</td>
                <td class="value text-danger pl-1">
                    <template v-if="cartMaster_edit.payment_paid_out">
                    {{-- <template v-if="cartMaster.payment"> --}}
                        <span class="paid-value badge" style="font-size: 16px;">
                            <strong v-text="parseFloat(cartMaster_edit.payment_paid_out).toFixed(2)"></strong>
                            <strong><small v-text="converJson(cartMaster.coin.name).en"></small></strong>
                        </span><br>
                    </template>
                </td>
            </tr>
            <tr>
                <td class="title">Payment {{__('admin.status')}}</td>
                <td class="title">
                    <select class="form-control" v-model="cartMaster_edit.payment_status" v-if="moode">
                        <option v-for="ps in p_status" :value="ps.id">@{{ JSON.parse(ps.name).en ?? null}}</option>
                    </select>
                    <span class="text-white col-sm-6" :class="classNameChange(cartMaster_edit.payment_status)" v-text="cartMaster_edit.payment_status_en"></span>
                </td>
            </tr>
            <tr>
                <td class="title">{{__('admin.date')}}</td>
                <td class="author">@{{cartMaster.registered_at??cartMaster.created_at}}</td>
            </tr>
            <tr>
            <tr v-if="cartMaster.retrieved_code">
                <td class="title">Retrieved Code</td>
                <td>
                    <span class="author text-danger" v-text="cartMaster.retrieved_code"></span>
                </td>
            </tr>
            <tr v-if="cartMaster.retrieved_value">
                <td class="title">Retrieved ِAmount</td>
                <td>
                    <span class="text-primary" v-text="cartMaster.retrieved_value"></span>
                    <strong><small class="text-primary" v-text="converJson(cartMaster.coin.name).en"></small></strong>
                </td>
            </tr>
            <tr v-if="cert_lms(cartMaster_edit.payment_status)">
                <td colspan="2" class="title">@include('crm::products_demand.invoice')</td>
            </tr>

        </table>
    </div>
</div>
