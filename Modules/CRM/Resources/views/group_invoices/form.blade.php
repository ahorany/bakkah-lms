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

                        <?php
                            $type_id = $cartMaster->type_id??null;
                            if(is_null($type_id)){
                                if($post_type=='rfq_invoices'){
                                    $type_id =  373;
                                }elseif($post_type=='group_invoices'){
                                    $type_id =  372;
                                }elseif($post_type=='b2b_invoices'){
                                    $type_id =  370;
                                }else{
                                    $type_id =  0; //b2b
                                }
                            }
                        ?>

                        {!! Builder::Hidden('master_id', $cartMaster->id??0) !!}
                        {!! Builder::Hidden('rfp_group_id', $cartMaster->rfpGroup->id??0) !!}

                        {!! Builder::Hidden('type_id', $type_id??0) !!}
                        {{-- {!!Builder::Hidden('type_id', 'type_id', $types, $type_id??0, ['col'=>'col-md-6'])!!} --}}

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

                            @if($post_type!='group_invoices')
                                <div class="col-md-3 accounting_sys_invoice">
                                    <div class="form-group">
                                        <label>{{__('admin.Upload Candidates')}}</label>
                                        <input type="file" name="excel_file"class="form-control @error('excel_file') is-invalid @enderror" placeholder="{{__('education.upload_users')}}">
                                    </div>
                                </div>
                                <div class="col-md-3 accounting_sys_invoice">
                                    <div class="form-group">
                                        <label>Template to add candidates</label><br>
                                        <a href="{{CustomAsset('upload/excel/candidates.xlsx')}}" download="" class="btn btn-sm btn-warning"><span><i class="fas fa-download p-1 main-color"></i> {{__('education.Download Excel')}}</span></a>
                                    </div>
                                </div>
                            @endif

                            {{-- Start of Add candidate from CRM --}}
                            {{-- @if($type_id == 372)
                                {!!Builder::Input('crm_invoice_number', 'crm_invoice_number', null, ['col'=>'col-md-3',
                                'placeholder'=>__('admin.invoice_number'),])!!}
                                <button data-id="{{$cartMaster->id??0}}" class="align-self-center btn btn-sm btn-success btn-storeCandidate" style="margin-top: 15px;visibility:visible;">
                                    <i class="fa fa-check"></i> {{__('admin.Add Candidate')}}
                                </button>
                            @endif --}}
                            {{-- End of Add candidate from CRM --}}
                        @endif

                        <br>
                        @if(isset($eloquent))
                            <div class="col-md-12 mt-2">
                                <div class="candidates-form">
                                    @include('crm::group_invoices.candidates')
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                @include('crm::group_invoices.totals')
                            </div>

                            @if(isset($cartMaster))
                                <div class="col-lg-12 col-md-12">
                                    <div class="card card-default">
                                        <div class="card-header"><h5 class="mb-0 float-left mt-2" style="color: #fb4400;"><i class="fas fa-bolt"></i> {{__('education.Actions')}}</h5></div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                @if($type_id == 373)
                                                    <a  class="btn btn-sm btn-primary" href="{{route('crm::rfq.register.edit', ['id'=>$cartMaster->id])}}" target="_blank" ><i class="fa fa-pencil"></i>  <span>{{__('admin.edit_order')}}</span></a>
                                                    {{-- <a  class="btn btn-sm btn-primary" href="{{env('APP_URL').'rfq/register/'.$cartMaster->id.'/edit'}}" target="_blank" ><i class="fa fa-pencil"></i>  <span>{{__('admin.edit_order')}}</span></a> --}}
                                                @endif

                                                {{-- <a target="_blank" href="{{route('training.carts.invoice', $cartMaster->id??0)}}" class="align-self-center btn btn-sm btn-primary"><span>{{__('admin.invoice')}}</span></a> --}}

                                                <a target="_blank" href="{{route('crm::rfq.exportQuotationToDoc', $cartMaster->id??0)}}" class="btn btn-sm btn-success"><span>{{__('admin.Download Quotation')}}</span></a>

                                                <a target="_blank" href="{{route('crm::rfq.exportInvoiceToDoc', $cartMaster->id??0)}}" class="btn btn-sm btn-warning"><span>{{__('admin.Download Invoice')}}</span></a>
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

    {{-- @if(isset($all_courses)) --}}
        <div id="app" class="card card-default">
            <div class="card-header">Session</div>
            <div class="card-body">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <label>{{__('admin.course_name')}}</label>
                        <?php
                        $val_course_id = $cartMaster->rfpGroup->course_id??null;
                        request()->val_course_id = $val_course_id;
                        ?>
                        <select name="course_id" @change="courseChange($event.target.value, 'change')" class="form-control @error('course_id') is-invalid @enderror ">
                            <option value="-1">{{__('admin.choose_value')}}</option>
                            @foreach($all_courses as $all_course)
                                <option value="{{$all_course->id}}" {{($val_course_id==$all_course->id)?'selected="selected"':''}}>{{$all_course->trans_title}}</option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <div class="form-group">
                        <label>{{__('admin.session_id')}}</label>
                        <?php
                            $val_session_id = $cartMaster->rfpGroup->session_id??null;
                            request()->val_session_id = $val_session_id;
                        ?>
                        <select v-model="val_session_id" name="session_id" @change="SessionChange($event.target.value)" class="form-control @error('session_id') is-invalid @enderror ">
                            <option value="-1">@{{ session_choose_value }}</option>
                            <option v-for="(list , index) in sessions" :value="list.id" :selected="list.id==val_session_id">@{{list.json_title}}</option>
                        </select>
                        @error('session_id')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                {{-- hidden --}}
                                <td>{{__('admin.price')}}</td>
                                <td v-html="cart_master.price"></td>
                                <input type="hidden" name="price" :value="cart_master.price">
                                <input type="hidden" name="training_option_id" :value="cart_master.training_option_id">
                            </tr>
                            <tr v-if="cart_master.discount != null && cart_master.discount != 0">
                                <td>{{__('admin.discount')}} % (<span v-html="cart_master.discount"></span>)</td>
                                <input type="hidden" name="discount_id" :value="cart_master.discount_id">
                                <input type="hidden" name="discount" :value="cart_master.discount">
                                <td>
                                    -<span v-html="cart_master.discount_value"></span>
                                </td>
                                <input type="hidden" name="discount_value" :value="cart_master.discount_value">
                            </tr>

                            <tr>
                                <td>{{__('admin.exam_price')}}</td>
                                <td v-html="cart_master.exam_price"></td>
                                <input type="hidden" name="exam_price" :value="cart_master.exam_price">
                            </tr>
                            <tr>
                                <td>{{__('admin.take2_price')}}</td>
                                <td v-html="cart_master.take2_price"></td>
                                <input type="hidden" name="take2_price" :value="cart_master.take2_price">
                            </tr>
                            <tr>
                                <td>{{__('admin.exam_simulation_price')}}</td>
                                <td v-html="cart_master.exam_simulation_price"></td>
                                <input type="hidden" name="exam_simulation_price" :value="cart_master.exam_simulation_price">
                            </tr>
                            <tr>
                                <td>{{__('admin.sub-total')}}</td>
                                <td v-html="cart_master.total"></td>
                                <input type="hidden" name="total" :value="cart_master.total">
                            </tr>
                            <tr>
                                <td>{{__('admin.VAT')}} % (<span v-html="cart_master.vat"></span>)</td>
                                <td v-html="cart_master.vat_value"></td>
                                <input type="hidden" name="vat" :value="cart_master.vat">
                                <input type="hidden" name="vat_value" :value="cart_master.vat_value">
                            </tr>
                            <tr>
                                <td><b>{{__('admin.total_after_vat')}}</b></td>
                                <td v-html="cart_master.total_after_vat"></td>
                                <input type="hidden" name="total_after_vat" :value="cart_master.total_after_vat">
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            {{-- @include('crm::products_demand.notes', ['master' => $cartMaster??0]) --}}
        </div>
    </div>
</div>

<script>
window.cartMaster = {!! $cartMaster??null !!}
window.sessions = {!! $session_array??null !!}
window.carts = {!! $cartMaster->carts??null !!}
window.choose_value = {!! json_encode(__('admin.choose_value')) !!}
// console.log(window.cartMaster);
// let sumsArray = {};
// var subTotal = 0;
// var discount = 0;
// var discount_value = 0;
// carts.forEach(item => {
//     // console.log(item);
//     subTotal += parseFloat(item.price);
//     discount_value += parseFloat(item.discount_value);
//     console.log(item.price);
// });
// console.log(subTotal);
// console.log(sumsArray)
var training_option_id = -1;
var price = 0;
var discount_id = null;
var discount = 0;//
var discount_value = 0;
var exam_price = 0;
var take2_price = 0;
var exam_simulation_price = 0;
var total = 0;
var vat = 0;
var vat_value = 0;
var total_after_vat = 0;

// console.log(cartMaster.rfp_group);
if(cartMaster.rfp_group)
{
    // console.log(cartMaster.rfp_group.session.training_option_id);
    training_option_id = cartMaster.rfp_group.session.training_option_id;
    price = cartMaster.rfp_group.price;
    discount_id = cartMaster.rfp_group.discount_id;//
    discount = cartMaster.rfp_group.discount;//
    discount_value = cartMaster.rfp_group.discount_value;
    exam_price = cartMaster.rfp_group.exam_price;
    take2_price = cartMaster.rfp_group.take2_price;
    exam_simulation_price = cartMaster.rfp_group.exam_simulation_price;
    total = cartMaster.rfp_group.total;
    vat = cartMaster.rfp_group.vat;
    vat_value = cartMaster.rfp_group.vat_value;
    total_after_vat = cartMaster.rfp_group.total_after_vat;
}
var vm = new Vue({
    el: '#app',
    data: {
        selected_session_id:-1,
        session_choose_value: window.choose_value,
        cartMaster: window.cartMaster,
        sessions: window.sessions,
        val_course_id: '{{request()->val_course_id??-1}}',
        val_session_id: '{{request()->val_session_id??-1}}',
        cart_master: {
            training_option_id:training_option_id,
            price:price,
            discount_id:discount_id,
            discount:discount,
            discount_value:discount_value,
            exam_price:exam_price,
            take2_price:take2_price,
            exam_simulation_price:exam_simulation_price,
            total:total,
            vat:vat,
            vat_value:vat_value,
            total_after_vat:total_after_vat,
        }
    },
    mounted () {
        this.courseChange(this.val_course_id, 'mounted');
        // this.SessionChange(this.val_session_id);
        // console.log(this.sessions);
    },
    watch: {
        val_session_id: function(val, oldVal){
            // alert(val);
            this.SessionChange(this.val_session_id);
        }
    },
    methods: {
        courseChange: function (val, method) {
            this.session_choose_value = 'choose_value';
            axios.get('{{route("crm::rfq.sessionsJson")}}', {
                params: {
                    'course_id' : val,
                    'val_session_id' : this.val_session_id,
                }
            })
            .then(function(resp){
                // console.log(resp);
                this.sessions = resp.data;
                if(method=='change')
                    this.val_session_id = -1;
                this.session_choose_value = window.choose_value;
                // console.log(this.session_choose_value);
            }.bind(this))
            .catch(function(err){
                console.log(err);
            });
            // this.selected_training_option_id = parseInt(index);
        },

        SessionChange: function (val) {
            this.session_choose_value = 'choose_value';
            axios.get('{{route("crm::rfq.SessionsDetailsJson")}}', {
                params: {
                    'session_id' : val
                }
            })
            .then(function(resp){
                console.log(resp.data);
                this.cart_master = resp.data;
                // this.sessions = resp.data;
                // this.session_choose_value = window.choose_value;
            }.bind(this))
            .catch(function(err){
                console.log(err);
            });
        },
    }
});
</script>


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
                url:"{{route('crm::rfq.register.storeCandidate')}}",
                data:{
                    master_id:id,
                    crm_invoice_number:crm_invoice_number,
                },
                success:function(data){
                    // console.log(data);
                    if(data == 'fail'){
                        alert('Somthing went wrong! Maybe you need to enter the interir invoice number of the candiadate.');
                    }else{
                        jQuery('.candidates-form').html(data);
                        alert('Reload the page to see the updates');
                        // location.reload();
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
