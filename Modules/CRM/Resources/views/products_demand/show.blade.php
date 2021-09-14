@extends(ADMIN.'.general.index')

@section('table')
	{{Builder::SetEloquent($cartMaster)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('cart')}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <link rel="stylesheet" href="{{CustomAsset(ADMIN.'-dist/css/jquery.datetimepicker.css')}}">
    <script src="{{CustomAsset(ADMIN.'-dist/js/jquery.datetimepicker.js')}}"></script>
<style>
.content-header {
    visibility: hidden;
    height: 10px;
}
tr td.field-title {
    width: 25%;
}
#note_list li:hover {
    background: rgba(0,0,0,.075) !important;
}
.badge{ max-width: 100%; }
.form-control{
    height: auto !important;
    padding: .1rem .75rem !important;
}
</style>
<div id="input">
    <div class="row">
        {{-- @dd($cartMaster); --}}
        <div class="col-md-9" id="crm_vue_app">
            <div class="row">

                <div class="col-md-8">
<?php
// use App\Helpers\Models\UserHelper;

// $UserHelper = new UserHelper();
// $main_email = 'Hani.salah78@gmail.com';
// $email_array      =  explode('@', $main_email);
// $username_from_email  =  str_replace(".","",$email_array['0']);
// $e_portal_username    = strtolower($username_from_email);
// $e_portal_password    = $e_portal_username.'@'.$UserHelper->random_str(3);
// $new_email    = strtolower($e_portal_username.'_b2b_bakkah__'.'@'.$email_array['1']);

// echo '<b>main_email: </b>'.$main_email.'<br>'.'<b>new_email: </b>'.$new_email.'<br>'.'<b>username: </b>'.$username_from_email.'<br>'.'<b>password: </b>'.$e_portal_password;

?>

                    @include('crm::products_demand.user-info')
                </div>
                <div class="col-md-4">
                    @include('crm::products_demand.invoice-master')
                </div>
            </div>
            @include('crm::products_demand.invoice-info')
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
            @include('crm::products_demand.notes', ['cartMaster' => $cartMaster])
        </div>
    </div>
</div>
@endsection

@push('vue')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.js.map"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-moment@4.1.0/dist/vue-moment.min.js"></script>
    <script>

    window.cartMaster = {!!json_encode($cartMaster??[])!!}
    window.payment = {!!json_encode($payment??[])!!}
    // console.log(window.cartMaster);
    window.countries = {!!json_encode($countries??[])!!}
    window.p_status = {!!json_encode($p_status??[])!!}
    window.paymentDetails = {!!json_encode($paymentDetails??[])!!}
    console.log(window.paymentDetails);

    if(typeof(window.cartMaster.user_id) == 'undefined' || window.cartMaster.user_id === null){
        toastr.error('There is no UserInfo!!', 'Error!');
        alert('There is no UserInfo!!');
        // window.location.href = 'https:\\bakkah.com';
        history.back();
    }

    var en_country = '';
    if(window.cartMaster.user_id.country_id??-1 != -1){
        en_country = (window.cartMaster.user_id.countries != null) ? JSON.parse(window.cartMaster.user_id.countries.name).en : '';
    }

    var payment_status_en = '';
    // alert(window.cartMaster.payment_status);
    if(window.cartMaster.payment_status != null){
        payment_status_en = (window.cartMaster.payment_status != null) ? JSON.parse(window.cartMaster.payment_status.name).en : '';
    }
    window.cartMaster_edit = {
        cartMasterId: {{$cartMasterId}},
        en_name: JSON.parse(window.cartMaster.user_id.name??'').en,
        ar_name: JSON.parse(window.cartMaster.user_id.name??'').ar,
        email: window.cartMaster.user_id.email,
        mobile: window.cartMaster.user_id.mobile,
        job_title: window.cartMaster.user_id.job_title,
        company: window.cartMaster.user_id.company,
        country_id: window.cartMaster.user_id.country_id??-1,
        en_country: en_country,
        username_lms: window.cartMaster.user_id.username_lms,
        password_lms: window.cartMaster.user_id.password_lms,
        payment_rep_id: window.cartMaster.payment_rep_id,
        retrieved_code: window.cartMaster.user_id.retrieved_code,
        balance: window.cartMaster.user_id.balance,
        invoice_number: window.cartMaster.invoice_number,
        reminder_date: window.cartMaster.reminder_date,
        total: window.cartMaster.total,
        vat_value: window.cartMaster.vat_value,
        total_after_vat: window.cartMaster.total_after_vat,
        payment_paid_in: window.payment?window.payment.paid_in:0,
        payment_paid_out: window.payment?window.payment.paid_out:0,
        payment_status: window.cartMaster.payment_status.id,
        payment_status_en: payment_status_en,
        carts: window.cartMaster.carts,
        course: 1,
    }
    // console.log(window.cartMaster_edit);
    var vm = new Vue({
        el:'#crm_vue_app',
        data:{
            cartMaster: window.cartMaster,
            cartMaster_edit:window.cartMaster_edit,
            countries: window.countries,
            p_status: window.p_status,
            payment_status_en: payment_status_en,
            paymentDetails: window.paymentDetails,
            errors: {},

            className: {
                '63' : 'badge badge-danger',
                '68' : 'badge badge-success',
                '376' : 'badge badge-success',
                '315'  :'badge badge-info',
                '316' : 'badge badge-warning',
                '317' : 'badge badge-info',
                '332' : 'badge badge-dark',
                '377' : 'badge badge-danger'
            },

            active:true,
            moode:false,
            editMode:true,
            one_time: true,
            cart_flags: {},
            carts_balance: {},
            sendMessages: false,
        },

        watch: {
          'cartMaster_edit.payment_status' : function(value){
                var item =  this.p_status.filter(function(item){
                    return item.id == value;
                } )
                this.cartMaster_edit.payment_status_en = JSON.parse(item[0].name).en;
          },

        },
        created(){
            // this.getData();
            let self = this;
            self.cartMaster_edit.carts.forEach( function(key) {
                self.cart_flags[key.id] = false;

                if(key.retrieved_value == 0){
                    self.carts_balance[key.id] = key.total_after_vat;
                    key.retrieved_value = key.total_after_vat;
                }
            });
            // console.log(self.carts_balance);

        },
        methods: {
            changeCartPaymentStatus: function(ps_val){

                let PS = [
                        {id: "63", name: "Not Complete"},
                        {id: "68", name: "Completed"},
                        {id: "315", name: "Bakkah Employee"},
                        {id: "316", name: "Refund"},
                        {id: "317", name: "PO"},
                        {id: "332", name: "Free Seat"},
                        {id: "376", name: "Transferred From"},
                        {id: "377", name: "Transferred To"},
                        {id: "0", name: "Invalid Payment Status"},
                    ];

                    var items =  PS.filter(function(item){
                                            return item.id == ps_val;
                                         });
                    return items[0].name;
            },
            is_transferred_to: function(ps_val){

                if(ps_val==377){ //Transferred To.
                    return true;
                }

                return false;
            },
            generate_code: function(){

                const hex = '0123456789ABCDEF';
                let output = '';
                for (let i = 0; i < 10; ++i) {
                    output += hex.charAt(Math.floor(Math.random() * hex.length));
                }
                return 'bakkah-'+output.toLowerCase();

            },
            update_balance: function(cart_id){
                const self = this;
                let cart_index = 0;
                let diff = 0;
                let diff_vat = 0;
                let diff_total = 0;

                self.cartMaster_edit.carts.forEach( function(element , key) {
                    if(element.id == cart_id){
                        cart_index = key;
                    }
                });

                var cart_edit = self.cartMaster_edit.carts[cart_index];

                if(cart_edit.payment_status == 377){ //Transferred To.
                    // console.log(this.cart_flags[cart_id])
                        if(! this.cart_flags[cart_id]){
                            this.carts_balance[cart_id] =  cart_edit.retrieved_value;
                            self.cartMaster_edit.retrieved_code = cart_edit.retrieved_code;

                            self.cartMaster_edit.balance = (parseFloat(self.cartMaster_edit.balance) + parseFloat(cart_edit.retrieved_value)).toFixed(2);

                            // console.log(cart_edit.retrieved_value, cart_edit.total_after_vat, cart_edit.coin_id);
                            if(cart_edit.retrieved_value != cart_edit.total_after_vat){
                                diff = (parseFloat(cart_edit.total_after_vat) - parseFloat(cart_edit.retrieved_value)).toFixed(2);
                                // if(cart_edit.coin_id == 334){
                                    diff_vat = diff * (cart_edit.vat / 100);
                                    diff = diff - diff_vat;
                                    diff_total = diff + diff_vat;
                                    // console.log(diff);
                                // }
                                self.cartMaster_edit.total  = (parseFloat(self.cartMaster_edit.total) + parseFloat(diff)).toFixed(2);
                                self.cartMaster_edit.vat_value  = (parseFloat(self.cartMaster_edit.vat_value) + parseFloat(diff_vat)).toFixed(2);

                                self.cartMaster_edit.total_after_vat  = (parseFloat(self.cartMaster_edit.total_after_vat) + parseFloat(diff_total)).toFixed(2);
                                self.cartMaster_edit.payment_paid_in  = (parseFloat(self.cartMaster_edit.payment_paid_in) + parseFloat(diff_total)).toFixed(2);
                            }

                            this.cart_flags[cart_id] = true;
                        }
                }

            },
            changeCartPaymentStatus_forCalc: function(cart_id, ps_val, event){

                // console.log(event.target.value)

                const self = this;
                var coin_id = self.cartMaster.coin_id;
                var cart_index = null;
                var is_add = true;
                var is_sub = true;

                self.cartMaster_edit.carts.forEach( function(element , key) {
                    if(element.id == cart_id){
                        cart_index = key;
                    }
                });

                var cart_edit = self.cartMaster_edit.carts[cart_index];

                if(ps_val==377){ //Transferred To.
                    cart_edit.retrieved_code = this.generate_code();
                    // cart_edit.retrieved_value = self.carts_balance[cart_id];
                }else{
                    if(this.cart_flags[cart_id]){

                        this.cart_flags[cart_id] = false;

                        self.cartMaster_edit.balance =(parseFloat(self.cartMaster_edit.balance) - parseFloat(self.carts_balance[cart_id])).toFixed(2);

                    }
                }

                // console.log(self.cartMaster_edit.cartMasterId, cart_id, ps_val, event);
                // axios.post("/crm/changeCartPaymentStatus_forCalc/",
                    axios.post("{{route('crm::show.changeCartPaymentStatus_forCalc')}}",
                    {
                        cartMasterId: self.cartMaster_edit.cartMasterId,
                        cart_id: cart_id,
                        payment_status: ps_val,
                        // coin_id: coin_id,
                        // event: event.target.checked,
                    })
                    .then(function(response){

                        var cart_payment_status = response['data']['cart_payment_status'];
                        // var total_after_vat_master_total = response['data']['cartMaster_total'];
                        // var total_after_vat_master_vat_value = response['data']['cartMaster_vat_value'];
                        // var total_after_vat_master_total_after_vat = response['data']['cartMaster_total_after_vat'];
                        var payment_paid_in = response['data']['payment_paid_in'];
                        var payment_paid_out = response['data']['payment_paid_out'];

                        var add_cases = [68,376]; // Completed , Transferred From, ,315,317 PO, Bakkah Employee
                        is_add = add_cases.includes(ps_val);
                        is_add_new = add_cases.includes(cart_payment_status);

                        if(is_add){
                            is_sub = false;
                        }
                        if(is_add == is_add_new){
                            is_add = false;
                            is_sub = false;
                        }

                        if (is_add){
                            // total_after_vat_master_total += cart_edit.total;
                            // total_after_vat_master_vat_value += cart_edit.vat_value;
                            // total_after_vat_master_total_after_vat += cart_edit.total_after_vat;
                            // payment_paid_in += cart_edit.total_after_vat;
                            payment_paid_in = parseFloat(payment_paid_in) + parseFloat(cart_edit.total_after_vat);
                            payment_paid_in = parseFloat(payment_paid_in).toFixed(2);
                            // self.cartMaster_edit.payment_paid_in = payment_paid_in;

                        }else if(is_sub){
                            // total_after_vat_master_total -= cart_edit.total;
                            // total_after_vat_master_vat_value -= cart_edit.vat_value;
                            // total_after_vat_master_total_after_vat -= cart_edit.total_after_vat;
                            // payment_paid_in -= cart_edit.total_after_vat;
                            payment_paid_in = parseFloat(payment_paid_in) - parseFloat(cart_edit.total_after_vat);
                            payment_paid_in = parseFloat(payment_paid_in).toFixed(2);
                        }

                        // self.cartMaster_edit.total = parseFloat(total_after_vat_master_total).toFixed(2);
                        // self.cartMaster_edit.vat_value = parseFloat(total_after_vat_master_vat_value).toFixed(2);
                        // self.cartMaster_edit.total_after_vat = parseFloat(total_after_vat_master_total_after_vat).toFixed(2);
                        // self.cartMaster_edit.payment_paid_in = parseFloat(payment_paid_in).toFixed(2);aaaaaaaaaaaaaaaa
                        self.cartMaster_edit.payment_paid_in = payment_paid_in;
                        self.cartMaster_edit.payment_paid_out = payment_paid_out;

                        if(ps_val != cart_payment_status){
                            axios.post("{{route('crm::show.addNote')}}",
                            {
                                cartMasterId: self.cartMaster_edit.cartMasterId,
                                comment: "Cart# "+cart_id+" it's old payment status was "+ self.changeCartPaymentStatus(cart_payment_status),
                                // here if refund you unroll the user from the LMS =========================
                            })
                            .then(function(response){
                                // console.log(response);
                                // if(response['status']==200){
                                //     toastr.success('Note added Successfully',response['statusText']);
                                // }
                            })
                            .catch(function(error){
                                console.log(error);
                            })
                        } //if

                        // console.log(response['data']);

                        if(response['data']['msg'] == 'error'){
                            toastr.error(response['data']['errors'], 'Error!')
                        }

                    })
                    .catch(function(error){
                        console.log(error);
                    })
                // // =====================

            },
            format:function(val){
                return moment(val).format('D MMM YYYY'); //LL
            },
            classNameChange: function(index){
                 return this.className[index];
            },
            converJson: function(val){
                return (val == isNaN || val == '') ? '' : JSON.parse(val);
            },
            cert_lms: function(val){
                // if($cartMaster->type_id != 374){
                //     if($cartMaster->payment_status==317 || $cartMaster->payment_status==315 || $cartMaster->payment_status==68 || $cartMaster->payment_status==376){
                //         $cert_lms = true;
                //     }
                // }

                switch (val){
                    case 68:
                    case 315:
                    case 317:
                    case 376:
                    case 332:
                        cert_lms = true;;
                        break;
                    default:
                        cert_lms = false;
                }
                return cert_lms;
            },
            send_invoice: function(){
                const self = this;
                var user = self.cartMaster.user_id;
                self.sendMessages = true;
                // console.log(self.cartMaster);
                // return;

                    axios.post("{{route('crm::show.SendEmailMaster')}}",
                    {
                        cartMasterId: self.cartMaster_edit.cartMasterId,
                        user: user,
                        // coin_id: coin_id,
                    })
                    .then(function(response){
                        // console.log(response['data']);
                        if(response['data']['msg'] == 'success'){
                            toastr.success('Email sent Successfully')
                        }

                        if(response['data']['msg'] == 'error'){
                            //this.sendMessages = false;
                            toastr.error(response['data']['errors'], 'Error!')
                        }

                        self.sendMessages = false;

                    })
                    .catch(function(error){
                        console.log(error);
                    })
                // // =====================
            },
            SessionChange: function (val, cart_id) {

                const self = this;
                var session_id = val;

                var cart_index = null;
                self.cartMaster_edit.carts.forEach( function(element , key) {
                    if(element.id == cart_id){
                        cart_index = key;
                    }
                });

                var cart_edit = self.cartMaster_edit.carts[cart_index];
                // console.log(cart_edit);
                // return;

                axios.post("{{route('crm::show.addNote')}}",
                    {
                        cartMasterId: self.cartMaster_edit.cartMasterId,
                        comment: "Cart# "+cart_id+" it's old sessions was SID("+cart_edit.session_id+") "+self.format(cart_edit.session.date_from),
                    })
                    .then(function(response){
                        // console.log(response);
                        // if(response['status']==200){
                        //     toastr.success('Note added Successfully',response['statusText']);
                        // }
                    })
                    .catch(function(error){
                        console.log(error);
                    })

                cart_edit.session_id = session_id;

                // var coin_id = self.cartMaster.coin_id;

                // axios.post("{{route('crm::show.SessionsDetailsJson')}}",
                // {
                //         cartMasterId:self.cartMaster_edit.cartMasterId,
                //         session_id:session_id,
                //         cart_id:cart_id,
                //         // exam_price:cart_edit.exam_price,
                //         // take2_price:cart_edit.take2_price,
                //         // exam_simulation_price:cart_edit.exam_simulation_price,
                // })

                // // axios.get("{{route('crm::show.SessionsDetailsJson')}}", {
                // //     params: {
                // //         // cartMasterId:self.cartMaster_edit.cartMasterId,
                // //         session_id:session_id,
                // //         cart_id:cart_id,
                // //         exam_price:cart_edit.exam_price,
                // //         take2_price:cart_edit.take2_price,
                // //         exam_simulation_price:cart_edit.exam_simulation_price,
                // //     }
                // // })
                // .then(function(response){
                //     // console.log(response['data']);
                //     // console.log(response['data']['discount_id']);
                //     cart_edit.session_id = session_id;
                //     // cart_edit.coin_id = coin_id;
                //     cart_edit.training_option_id = response['data']['training_option_id'];
                //     cart_edit.price = response['data']['price'];
                //     cart_edit.discount = response['data']['discount'];
                //     cart_edit.discount_id = response['data']['discount_id'];
                //     cart_edit.discount_value = response['data']['discount_value'];
                //     // cart_edit.exam_price = response['data']['exam_price'];
                //     // cart_edit.take2_price = response['data']['take2_price'];
                //     // cart_edit.exam_simulation_price = response['data']['exam_simulation_price'];
                //     cart_edit.exam_price = cart_edit.exam_price;
                //     cart_edit.take2_price = cart_edit.take2_price;
                //     cart_edit.exam_simulation_price = cart_edit.exam_simulation_price;
                //     cart_edit.total = response['data']['total'];
                //     cart_edit.vat = response['data']['vat'];
                //     cart_edit.vat_value = response['data']['vat_value'];
                //     cart_edit.total_after_vat = response['data']['total_after_vat'];

                //     self.cartMaster_edit.total = response['data']['cartMaster_total'];
                //     self.cartMaster_edit.vat_value = response['data']['cartMaster_vat_value'];
                //     self.cartMaster_edit.total_after_vat = response['data']['cartMaster_total_after_vat'];
                //     self.cartMaster_edit.payment_paid_in = response['data']['payment_paid_in'];
                //     // console.log(self.cartMaster_edit.carts[cart_index]);
                // })
                // .catch(function(error){
                //     console.log(error);
                // });

                /*
                const self = this;
                var session_id = -1;
                var coin_id = self.cartMaster.coin_id;
                var cart_index = null;

                var item_cart =  self.cartMaster.carts.filter(function(item){
                    return item.id == cart_id;
                });
                item_cart = item_cart[0];
                // console.log(item_cart.index);
                self.cartMaster_edit.carts.forEach( function(element , key) {
                    if(element.id == cart_id){
                        // console.log(key)
                        cart_index = key;
                    }
                });
                // console.log(cart_index)
                var item_session =  item_cart.training_option.sessions.filter(function(item){ return item.id == val; })
                // console.log(item_session[0].id);
                // console.log(self.cartMaster.coin_id);
                // return item_session[0];
                session_id = item_session[0].id;
                coin_id = self.cartMaster.coin_id;
                    // axios.get(`/crm/SessionsDetailsJson/${session_id}/${coin_id}`)
                    axios.get("{{route('crm::show.SessionsDetailsJson')}}", {
                        params: {
                            cartMasterId: self.cartMaster_edit.cartMasterId,
                            session_id:session_id,
                            coin_id:coin_id,
                        }
                    })
                    .then(function(response){
                       console.log(response['data']);

                       var cart_edit = self.cartMaster_edit.carts[cart_index];
                       cart_edit.session_id = session_id;
                       cart_edit.coin_id = coin_id;
                       cart_edit.training_option_id = response['data']['training_option_id'];
                       cart_edit.price = response['data']['price'];
                       cart_edit.discount = response['data']['discount'];
                       cart_edit.discount_id = response['data']['discount_id'];
                       cart_edit.discount_value = response['data']['discount_value'];
                       cart_edit.exam_price = response['data']['exam_price'];
                       cart_edit.take2_price = response['data']['take2_price'];
                       cart_edit.exam_simulation_price = response['data']['exam_simulation_price'];
                       cart_edit.total = response['data']['total'];
                       cart_edit.vat = response['data']['vat'];
                       cart_edit.vat_value = response['data']['vat_value'];
                       cart_edit.total_after_vat = response['data']['total_after_vat'];

                       self.cartMaster_edit.total = response['data']['cartMaster_total'];
                       self.cartMaster_edit.vat_value = response['data']['cartMaster_vat_value'];
                       self.cartMaster_edit.total_after_vat = response['data']['cartMaster_total_after_vat'];
                    //    console.log(self.cartMaster_edit.carts);
                    })
                    .catch(function(error){
                        console.log(error);
                    })
                */
            },
            addCartFeature: function(cart_id, training_option_feature_id, price, feature_id, event) {
                // console.log(cart_id, feature_id, price, event);

                const self = this;
                var coin_id = self.cartMaster.coin_id;
                var cart_index = null;

               self.cartMaster_edit.carts.forEach( function(element , key) {
                    if(element.id == cart_id){
                        cart_index = key;
                    }
                });

                var cart_edit = self.cartMaster_edit.carts[cart_index];

                    // axios.post("/crm/addCartFeature/",
                    axios.post("{{route('crm::show.addCartFeature')}}",
                    {
                        cartMasterId: self.cartMaster_edit.cartMasterId,
                        cart_id: cart_id,
                        feature_id: training_option_feature_id,
                        price: price,
                        payment_status: self.cartMaster_edit.payment_status,
                        // coin_id: coin_id,
                        event: event.target.checked,
                    })
                    .then(function(response){

                        // if (event.target.checked) {

                        cart_edit.total = response['data']['cart_total'];
                        cart_edit.vat_value = response['data']['cart_vat_value'];
                        cart_edit.total_after_vat = response['data']['cart_total_after_vat'];

                        self.cartMaster_edit.total = response['data']['cartMaster_total'];
                        self.cartMaster_edit.vat_value = response['data']['cartMaster_vat_value'];
                        self.cartMaster_edit.total_after_vat = response['data']['cartMaster_total_after_vat'];
                        self.cartMaster_edit.payment_paid_in = response['data']['payment_paid_in'];

                        // console.log(response['data']);

                        if(response['data']['msg'] == 'error'){
                            toastr.error(response['data']['errors'], 'Error!')
                        }

                    })
                    .catch(function(error){
                        console.log(error);
                    })

                    // console.log('update')

            },
            Features: function(cart_features, feature_item_id, feature_item_price, cart_id) {

                var _return_val = false;

                cart_features.forEach( function(element , key) {
                    if(feature_item_id == element.training_option_feature_id && cart_id == element.master_id){
                        _return_val = true;
                    }
                });
                // cart_features.forEach( function(element , key) {
                //     if(feature_item_id == element.training_option_feature_id && cart_id == element.master_id && feature_item_price == element.price){
                //         _return_val = true;
                //     }
                // });
                return _return_val;
            },

            Features_price: function(cart_features, feature_item_id, feature_item_price, cart_id) {

                var _return_val = null;

                cart_features.forEach( function(element , key) {
                    if(feature_item_id == element.training_option_feature_id && cart_id == element.master_id){
                        _return_val = element.price;
                    }
                });
                // cart_features.forEach( function(element , key) {
                //     if(feature_item_id == element.training_option_feature_id && cart_id == element.master_id && feature_item_price == element.price){
                //         _return_val = element.price;
                //     }
                // });
                return _return_val;
            },

            Switch: function(cartMaster){
                this.moode = this.moode === false ? true : false;
                this.active = this.active === false ? true : false;
                if(this.moode==false)
                {
                    let self = this;

                    var item =  self.countries.filter(function(item){
                        return item.id == self.cartMaster_edit.country_id;
                    } )
                    if(item.length > 0){
                        self.cartMaster_edit.en_country = JSON.parse(item[0].name).en;
                    }

                    // axios.post("/crm/updateData/", self.cartMaster_edit)
                    axios.post("{{route('crm::show.updateData')}}", self.cartMaster_edit)
                    .then(function(response){

                        if(response['data']['msg'] != 'error'){
                            // console.log(self.cartMaster_edit.carts);

                            self.cartMaster_edit.total = response['data']['cartMaster_total'];
                            self.cartMaster_edit.vat_value = response['data']['cartMaster_vat_value'];
                            self.cartMaster_edit.total_after_vat = response['data']['cartMaster_total_after_vat'];
                            self.cartMaster_edit.payment_paid_in = response['data']['payment_paid_in'];
                            self.cartMaster_edit.payment_paid_out = response['data']['payment_paid_out'];

                            if (self.moode == false){
                                self.errors ={};
                                toastr.success('Updated Data Successfully',response.data)
                                window.location.reload();
                            }

                            //notes.getNote();

                        }else{
                            self.errors =  response['data']['errors']
                            for (let property in self.errors) {
                                self.errors[property] = self.errors[property][0];
                            }

                            // toastr.error(response['data']['errors'], 'Error!')
                            // toastr.success('Error',self.errors)
                        }

                        if(response['data']['msg'] == 'error'){
                            toastr.error(response['data']['errors'], 'Error!')
                        }
                        // console.log(response['data']);
                    })
                    .catch(function(error){
                        console.log(error);
                    })

                    // console.log('update')
                }
            },


        }

    });
    </script>
@endpush
