@extends(ADMIN.'.general.index')

@section('table')
	{{Builder::SetEloquent($cartMaster)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('cart')}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
<style>
    tr td.field-title {
        width: 25%;
    }
    #note_list li:hover {
        background: rgba(0,0,0,.075) !important;
    }
    .badge{ max-width: 100%; }
</style>
    <div id="input">
    <div class="row">
        <div class="col-md-9">
            <div class="card">

                <div class="card-header">
                    <h6 class="mb-0 float-left"><i class="far fa-file-alt" aria-hidden="true"></i> Order Details</h6>
                    <h6 class="mb-0 float-right">{{$cartMaster->registered_at}}</h6>
                </div>

                <div class="card-body p-0" id="crm_vue_app">

                    <table class="table table-striped table-hover table-total-info" :key="cartMaster.id">{{--v-for="cart in carts" --}}
                        <tr class="text-center">
                            <td colspan="2"><b>User Info</b>
                                <button class="" v-if="active" style="float: right" @click.prevent="Switch(cartMaster)" class="btn btn-primary btn-sm"  type="submit" ><li class="fa fa-edit"></li> <span style="font-family: 'Lato-Regular';"> edit</span></button>
                                <button v-else style="float: right" @click.prevent="Switch(cartMaster)" class="btn btn-warning btn-sm"><li class="fa fa-save"></li><span style="font-family: 'Lato-Regular';">  update</span></button>
                            </td>
                        </tr>

                        <tr>
                            <td class="field-title">{{__('admin.name')}}</td>
                            <td>
                                <div v-if="moode" style="display: flex;">
                                    <input type="text" class="form-control col-sm-6" v-model="cartMaster_edit.en_name" placeholder="Arabic">
                                    <input type="text" class="form-control col-sm-6" v-model="cartMaster_edit.ar_name" placeholder="Arabic">
                                </div>
                                <div v-else >
                                     <span class="text-secondary col-sm-6">En: @{{cartMaster_edit.en_name}}</span>
                                     <span class="text-secondary col-sm-6">Ar: @{{cartMaster_edit.ar_name}}</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="field-title">{{__('admin.email')}}</td>
                            <td>
                                {{-- <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.email"> --}}
                                <span class="text-secondary">@{{cartMaster_edit.email}}</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="field-title">{{__('admin.mobile')}}</td>
                            <td>
                                <input v-if="moode" type="number" class="form-control" v-model="cartMaster_edit.mobile">
                                <span v-else class="text-secondary">@{{cartMaster_edit.mobile}}</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="field-title">{{__('admin.job_title')}}</td>
                            <td>
                                <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.job_title">
                                <span v-else class="text-secondary">@{{cartMaster_edit.job_title}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-title">{{__('admin.company')}}</td>
                            <td>
                                <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.company">
                                <span v-else class="text-secondary">@{{cartMaster_edit.company}}</span>
                            </td>
                        </tr>

                        <tr>
                            <td class="field-title">{{__('admin.country')}}</td>
                            <td>
                                @if($cartMaster->userId->country_id != null)
                                    <select class="form-control" v-model="cartMaster_edit.country_id" v-if="moode">
                                        <option v-for="country in countries" :value="country.id">@{{ JSON.parse(country.name).en ?? null}}</option>
                                    </select>
                                @else
                                    <span v-else class="text-secondary">@{{cartMaster_edit.en_country}}</span>
                                @endif
                                <span v-else class="text-secondary">@{{cartMaster_edit.en_country}}</span>
                            </td>
                        </tr>

                        @if($cartMaster->type_id == 374)
                            <tr>
                                <td class="field-title">{{__('admin.username_lms')}}</td>
                                <td>
                                    <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.username_lms">
                                    <span v-else class="text-secondary">@{{cartMaster_edit.username_lms}}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="field-title">{{__('admin.password_lms')}}</td>
                                <td>
                                    <input v-if="moode" type="text" class="form-control" v-model="cartMaster_edit.password_lms">
                                    <span v-else class="text-secondary">@{{cartMaster_edit.password_lms}}</span>
                                </td>
                            </tr>
                        @endif

                        @if($cartMaster->type_id != 374)
                            <tr>
                                <td class="value" colspan="2">{{$cartMaster->rfpGroup->organization->en_title??null}}</td>
                            </tr>
                            <tr>
                                <?php
                                    $class = [
                                        357 => 'success', //Paid
                                        358 => 'dark', //PO
                                        356 => 'info', //Invoice
                                        355 => 'warning', //Pending
                                        359 => 'danger', //Cancel
                                    ];
                                ?>
                                <td class="value" colspan="2">
                                    @if($cartMaster->status_id)
                                        <span class="badge badge-{{$class[$cartMaster->status_id]??null}}">
                                            {{$cartMaster->status->trans_name??null}}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endif

                        <tr class="text-center">
                            <td colspan="2"><b>Invoice Info</b></td>
                        </tr>

                        <tr>
                            <td colspan="2">

                                <table class="table table-striped table-bordered table-hover table-condensed table-total-info mb-2">
                                    <tr style="border-top: 2px solid #fb4400;">
                                        <td class="">{{__('admin.invoice number')}}</td>
                                        <td colspan="5">
                                            <input v-if="moode" type="number" class="form-control" v-model="cartMaster_edit.invoice_number" >
                                            <span v-else class="text-secondary">@{{cartMaster_edit.invoice_number}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="title">{{__('admin.sub-total')}}</td>
                                        <td class="value text-secondary">{{$cartMaster->total??null}}</td>

                                        <td class="title">{{__('admin.vat')}}(<span style="color:#dc3545;">{{$cartMaster->vat}}%</span>)</td>
                                        <td class="value text-secondary">{{$cartMaster->vat_value}}</td>

                                        <td class="title">{{__('admin.total_after_vat')}}</td>
                                        <td class="value text-secondary">{{$cartMaster->total_after_vat??null}}</td>
                                    </tr>
                                    <tr>
                                        <td class="title">{{__('admin.amount')}}</td>
                                        <td class="value text-primary">
                                            @if(isset($cartMaster->payment->paid_in))
                                                <span class="paid-value badge {{($cartMaster->coin_id==334)?'badge-info':'badge-warning'}}" style="font-size: 15px;">
                                                <strong>{{($cartMaster->payment->paid_in!=0)?$cartMaster->payment->paid_in:$cartMaster->payment->paid_out}} <small>{{$cartMaster->coin->trans_name??null}}</strong></small>
                                                </span><br>
                                            @endif
                                        </td>

                                        <?php
                                            $class = [
                                                63 => 'danger',
                                                68 => 'success',
                                                376 => 'success',
                                                315 => 'info',
                                                316 => 'warning',
                                                317 => 'info',
                                                332 => 'dark'
                                            ];
                                        ?>
                                        <td class="title">Payment {{__('admin.status')}}</td>
                                        <td class="title">
                                            {{-- @if($cartMaster->payment_status)
                                                <span class="badge badge-{{$class[$cartMaster->payment_status]??null}}">
                                                    {{$cartMaster->paymentStatus->trans_name??null}}
                                                </span>
                                            @endif --}}
                                            @if($cartMaster->payment_status)
                                                <select class="form-control" v-model="cartMaster_edit.payment_status" v-if="moode">
                                                    <option v-for="ps in p_status" :value="ps.id">@{{ JSON.parse(ps.name).en ?? null}}</option>
                                                </select>
                                            @else
                                                <span class="text-white col-sm-6" :class="classNameChange(cartMaster_edit.payment_status)" v-text="payment_status_en"></span>
                                            @endif
                                            <span class="text-white col-sm-6" :class="classNameChange(cartMaster_edit.payment_status)" v-text="payment_status_en"></span>
                                        </td>

                                        <td class="title">{{__('admin.date')}}</td>
                                        <td class="author">{{$cartMaster->registered_at}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr class="text-center">
                            <td colspan="2"><b>Course Info</b></td>
                        </tr>
                        <tr>
                            <td class="field-title p-0" colspan="2">

                                {{-- ========================= Course ========================== --}}

                                @include('crm::products_demand.courses', ['cartMaster'=>$cartMaster])
                                {{-- @include('training.carts.table-parts.courses', ['cartMaster'=>$cartMaster]) --}}

                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            {{-- @include('crm::products_demand.user_history', ['carts' => $cartMaster->userId->carts]) --}}
        </div>
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0 float-left"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</h6>
                    <h6 class="mb-0 float-right">Hello {{auth()->user()->trans_name}}</h6>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" onclick="window.history.back();">Return Back</button>
                </div>
            </div>

            {{-- @include('crm::products_demand.notes', ['notes' => $cart->notes]) --}}
            @include('crm::products_demand.notes', ['master' => $cartMaster])
        </div>
    </div>
    </div>
@endsection

@push('vue')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.js.map"></script>
    <script>
        // window.status = {!!json_encode($status??[])!!}
        window.cartMaster = {!!json_encode($cartMaster??[])!!}
        // window.carts = {!!json_encode($carts??[])!!}
        // console.log(window.cart);
        window.countries = {!! json_encode($countries ?? []) !!}
        window.p_status = {!! json_encode($p_status ?? []) !!}
        // window.courses = {!! json_encode($courses ?? []) !!}
        window.trainingOptions = {!! json_encode($trainingOptions ?? []) !!}
        // window.sessions = {!! json_encode($session_array ?? []) !!}
        // window.delivery_methods = {!! json_encode($delivery_methods ?? []) !!}
    var vm =new Vue({
        el:'#crm_vue_app',
        data:{
            className: {
                '63' : 'badge badge-danger',
                '68' : 'badge badge-success',
                '376' : 'badge badge-success',
                '315'  :'badge badge-info',
                '316' : 'badge badge-warning',
                '317' : 'badge badge-info',
                '332' : 'badge badge-dark'
            },
            constant:'',
            cartMaster_edit:{
                en_name: JSON.parse(cartMaster.user_id.name).en,
                ar_name: JSON.parse(cartMaster.user_id.name).ar,
                email: cartMaster.user_id.email,
                mobile: cartMaster.user_id.mobile,
                job_title: cartMaster.user_id.job_title,
                company: cartMaster.user_id.company,
                country_id: cartMaster.user_id.country_id,
                en_country: JSON.parse(cartMaster.user_id.countries.name).en,
                username_lms: cartMaster.user_id.username_lms,
                password_lms: cartMaster.user_id.password_lms,
                invoice_number: cartMaster.invoice_number,
                payment_status: cartMaster.payment_status.id,

                // carts:{'items':cartMaster.carts},

                // // course_id: cart.course_id,
                // session_id: cart.session_id,
                // training_option_id: cart.training_option_id,
                // course_title: JSON.parse(cart.course.title).en + ' | <strong>' + JSON.parse(cart.training_option.type.name).en + '</strong>',
                // option_type_name: JSON.parse(cart.training_option.type.name).en,
                // price: cart.price,
            },
            // courses:courses,
            trainingOptions:trainingOptions,
            errors:[],
            // carts:cartMaster.carts,
            carts:cartMaster.carts,
            cartMaster:cartMaster,
            payment_status_en: JSON.parse(cartMaster.payment_status.name).en,
            // cart_payment_status_en: JSON.parse(carts.payment_status.name).en,
            active:true,
            moode:false,
            url:'http://localhost:8000/crm/products-demand/session/list',
            editMode:true,
            // delivery_methods:window.delivery_methods,
            // selected_session_id:-1,
            // session_choose_value: window.choose_value,
            // sessions: window.sessions,
        },
        // mounted() {
        //     // this.constant = JSON.parse(this.carts[0].course.title).en;
        // },

        watch: {
          'cartMaster_edit.payment_status' : function(value){
                var item =  p_status.filter(function(item){
                                            return item.id == value;
                                         } )
                this.payment_status_en = JSON.parse(item[0].name).en;
          },
        //   'cart.payment_status' : function(value){
        //         var item =  p_status.filter(function(item){
        //                                     return item.id == value;
        //                                  } )
        //         this.cart_payment_status_en = JSON.parse(item[0].name).en;
        //   }
        },
        methods: {
            changeCartPaymentStatus: function(val){

                let PS = [
                        {id: "63", name: "Not Complete"},
                        {id: "68", name: "Completed"},
                        {id: "315", name: "Bakkah Employee"},
                        {id: "316", name: "Refund"},
                        {id: "317", name: "PO"},
                        {id: "332", name: "Free Seat"},
                        {id: "376", name: "Transferred From"},
                        {id: "377", name: "Transferred To"},
                    ];

                    var items =  PS.filter(function(item){
                                            return item.id == val;
                                         })
                    return items[0].name;
            },
            format:function(val){
                return val;
                return dateFormat(new Date(val), 'dS mmmm yyyy')
            },
            classNameChange: function(index){
                 return this.className[index]
            },
            Switch: function(cartMaster){

                //  console.log(this.trainingOptions)
                //  console.log(this.carts[0].cart_features)
                //  console.log(cartMaster.payment_status)
                //  cartMaster->paymentStatus->trans_name
                //  console.log(JSON.parse(cartMaster.payment_status.name).en)

                // console.log(this.cartMaster_edit.payment_status.id)

                this.moode = this.moode === false ? true : false;
                this.active = this.active === false ? true : false;
                if(this.moode==false)
                {
                    let self = this;

                   var item =  countries.filter(function(item){ return item.id == self.cartMaster_edit.country_id;} )
                    // console.log(JSON.parse(item[0].name).en);
                    this.cartMaster_edit.en_country = JSON.parse(item[0].name).en;
                    axios.post("{{route('crm::carts.update')}}",{
                        params: {
                            id: this.cartMaster.id,
                            cartMaster_edit: this.cartMaster_edit,
                            carts: this.carts,
                            // carts: JSON.parse(this.carts),
                            // aaa: this.aaa,
                        }
                    }).then(response => {
                        console.log('aaaaaaaaa');
                        console.log(response.data);
                        if (this.moode == false){
                            toastr.success('Updated Data Successfully',response.data)
                        }
                        this.cart.course_id.push(cart.course_id)
                        console.log(response.data);

                    }).catch(error=>{

                        // console.log(error.response);
                        // console.log(error.response.data.errors);
                        // this.errors = [];
                        // if (error.response.data.errors.name){
                        //     this.errors.push(error.response.data.errors.name[0])
                        //     toastr.error(error.response.data.errors.name[0], 'Invalid Data!')
                        // }
                        // if (error.response.data.errors.email){
                        //     this.errors.push(error.response.data.errors.email[0])
                        //     toastr.error(error.response.data.errors.email[0], 'Invalid Data!')
                        // }
                        // if (error.response.data.errors.mobile){
                        //     this.errors.push(error.response.data.errors.mobile[0])
                        //     toastr.error(error.response.data.errors.mobile[0], 'Invalid Data!')
                        // }
                        // if (error.response.data.errors.username_lms){
                        //     this.errors.push(error.response.data.errors.username_lms[0])
                        //     toastr.error(error.response.data.errors.username_lms[0], 'Invalid Data!')
                        // }
                    })
                }
            },
        }

    });
    </script>
@endpush
