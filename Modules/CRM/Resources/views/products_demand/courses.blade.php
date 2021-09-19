<div class="row" v-for="(cart, index) in cartMaster_edit.carts">
    <div class="col-md-9">
    <table class="table table-striped table-bordered table-condensed table-total-info mb-2">
        <thead>
            <tr class="table-primary">
                <th colspan="3" width="80%">
                    <span class="value">
                    @{{converJson(cart.course.title).en}} | @{{converJson(cart.training_option.type.name).en}}
                    </span>
                    <small class="text-secondary pl-2">
                        SID: @{{cart.session.id??null}}
                    </small>
                </th>
                <th class="text-secondary font-weight-normal" colspan="1" width="20%" style="font-size: 13px;">Item #: @{{cart.id??null}}</th>
            </tr>
        </thead>
        <tbody>
            <template v-if="cartMaster.type_id != 374">
                <tr>
                    <td class="title text-secondary col-md-3">@{{index+1}}) @{{JSON.parse(cart.user_id.name).en ?? null}}</td>
                    <td colspan="3" class="value text-secondary col-md-3" style="font-size: 13px;">@{{cart.user_id.email ?? null}}
                        <template v-if="cart.user_id.main_email">
                            <br>
                            <b>Excel Email: </b>@{{cart.user_id.main_email ?? null}}
                        </template>
                    </td>
                </tr>
                <tr>
                    <td class="title col-md-3">{{__('admin.username_lms')}}: <span class="text-secondary ">@{{cart.user_id.username_lms ?? null}}</span></td>
                    <td colspan="3" class="value col-md-3" style="font-size: 13px;">{{__('admin.password_lms')}}: <span class="text-secondary ">@{{cart.user_id.password_lms ?? null}}</span></td>
                </tr>
            </template>
            <tr>
                <td class="title col-md-3">Date</td>
                <td colspan="3" class="value text-bold col-md-3">
                    <select v-if="moode" class="form-control" name="session_id" @change="SessionChange($event.target.value, cart.id)">
                        <option value="-1">choose value</option>
                        <option v-for="(list , index) in cart.training_option.sessions" :value="list.id" :selected="list.id==cart.session_id">SID: @{{list.id}} |  ( @{{format(list.date_from)}}-@{{format(list.date_to)}} )</option>
                    </select>
                    {{-- <span v-else style="font-size: 14px;">
                        @{{format(cart.session.date_from)??null}} -  @{{format(cart.session.date_to)??null}}
                    </span> --}}
                    <span style="font-size: 14px;">
                        @{{format(cart.session.date_from)??null}} -  @{{format(cart.session.date_to)??null}}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="title col-md-3">Invoice No.</td>
                <td colspan="3" class="value text-secondary col-md-3" style="font-size: 13px;">@{{cart.invoice_number??null}}</td>
            </tr>
            <tr v-if="cart.group_invoice_id != null">
                <td class="title"><span class="badge badge-danger">Group</span></td>
                <td class="value" colspan="3"><span class="badge badge-secondary">MID: @{{cart.group_invoice_id??null}}</span></td>
            </tr>
            <tr>
                <td class="title">Price before VAT</td>
                <td class="value text-secondary" colspan="3" style="font-size: 13px;">@{{cart.price}}</td>
            </tr>
            <tr>
                <td class="title">Discount(<span style="color:#28a745;">-@{{cart.discount}}%</span>)
                </td>
                <td class="value text-secondary" colspan="3" style="font-size: 13px;">-@{{cart.discount_value}}
                    <template v-if="cart.promo_code != null">
                        <small class="ml-2 paid-value badge badge-danger font-weight-normal">@{{cart.promo_code}}</small>
                    </template>
                </td>
            </tr>
            <tr>
                {{-- font-weight-bold --}}
                <td class="title">Course Price after discount (Without VAT)</td>
                <td class="value" colspan="3">@{{parseFloat(((cart.price - cart.discount_value)*100)/100).toFixed(2)}}
                 <span class="text-primary">and (with VAT) = @{{parseFloat((((cart.price - cart.discount_value)*100)/100) * ("1."+(cart.vat))).toFixed(2)}}</span>
                 {{-- Math.round --}}
                </td>
            </tr>
            <tr>
                <td class="title">Net Total (Without VAT)</td>
                <td class="value text-bold" colspan="3">@{{cart.total}}</td>
            </tr>
            <tr>
                <td class="title">VAT(<span style="color:#dc3545;">@{{cart.vat}}%</span>)</td>
                <td class="value" colspan="3">@{{cart.vat_value}}</td>
            </tr>
            <tr>
                <td class="title">Net Total (With VAT)</td>
                <td class="value text-bold text-primary" colspan="3">@{{cart.total_after_vat}}
                    <strong><small v-text="converJson(cart.coin.name).en"></small></strong>
                </td>
            </tr>
            <tr>
                <template v-if="cart.course">
                    <template v-if="cart.course.partner_id == 12">
                    {{-- Watson Martin (WM) --}}
                        <tr>
                            <td class="author" colspan="2">
                                <a :href="'{{url('sessions')}}/' + cart.id + '/cipd'" class="btn btn-info btn-xs mb-1" target="_blank"><i class="fa fa-eye" aria-hidden="true" style="font-size: 14px;"></i> Show CIPD Filled Form</a>
                            </td>
                            <td class="author" colspan="3">
                                <a :href="'{{url('sessions')}}/' + cart.id + '/exportCipdToDoc'" class="btn btn-dark btn-xs mb-1" target="_blank"><i class="fa fa-download" aria-hidden="true" style="font-size: 14px;"></i> Download CIPD Filled Form</a>
                            </td>
                        </tr>
                    </template>
                </template>
            </tr>
        </tbody>
    </table>
    </div>
    <div class="col-md-3">
        <table class="table table-striped table-bordered table-condensed table-total-info">
            <thead>
                <tr class="table-primary">
                    <th>Features</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="feature_item in (cart.training_option.training_option_feature)">
                    <td>
                        <label v-if="feature_item.is_include" class="d-flex justify-content-between m-0" style="font-weight: normal;white-space: pre-line;">
                            <span class="d-flex align-items-center">
                                <span style="width: 20px;" class="mr-2"><i class="fas fa-plus-square main-color"></i></span>
                                @if(App::isLocale('en'))
                                    <span v-html="JSON.parse(feature_item.feature.title).en"></span>
                                @else
                                    <span v-html="JSON.parse(feature_item.feature.title).ar"></span>
                                @endif
                                <small v-if="cartMaster.coin_id == 334" class="badge badge-primary badge-pill mx-1 font-weight-normal" v-html="feature_item.price"></small>
                                <small v-else class="badge badge-primary badge-pill mx-1 font-weight-normal" v-html="feature_item.price_usd"></small>

                                <template v-if="cartMaster.coin_id == 334 && Features(cart.cart_features, feature_item.id, feature_item.price, cart.id) == true">
                                    <small class="font-weight-bold badge badge-warning badge-pill mx-1"> @{{Features_price(cart.cart_features, feature_item.id, feature_item.price, cart.id)}}</small>
                                </template>
                                <template v-if="cartMaster.coin_id == 335 && Features(cart.cart_features, feature_item.id, feature_item.price_usd, cart.id) == true">
                                    <small class="font-weight-bold badge badge-warning badge-pill mx-1"> @{{Features_price(cart.cart_features, feature_item.id, feature_item.price_usd, cart.id)}}</small>
                                </template>
                                <small class="text-danger" v-html=" JSON.parse(feature_item.excerpt).en" v-if="feature_item.feature_id == 5"></small>
                            </span>
                        </label>
                        <label v-else class="d-flex justify-content-between m-0" style="font-weight: normal;white-space: pre-line;">
                            <span class="d-flex align-items-center">
                                <span style="width: 20px;">
                                    <template v-if="cartMaster.coin_id == 334">
                                        <input v-if="moode" type="checkbox" @click="addCartFeature(cart.id, feature_item.id, feature_item.price, feature_item.feature_id, $event)" :checked="Features(cart.cart_features, feature_item.id, feature_item.price, cart.id)" value="1">
                                    </template>
                                    <template v-else>
                                        <input v-if="moode" type="checkbox" @click="addCartFeature(cart.id, feature_item.id, feature_item.price_usd, feature_item.feature_id, $event)" :checked="Features(cart.cart_features, feature_item.id, feature_item.price_usd, cart.id)" value="1">
                                    </template>
                                    <i class="far fa-check-square" :class="[Features(cart.cart_features, feature_item.id, feature_item.price, cart.id) && !moode ? '' : 'hide']"></i>
                                </span>
                                @if(App::isLocale('en'))
                                    <span v-html="JSON.parse(feature_item.feature.title).en"></span>
                                @else
                                    <span v-html="JSON.parse(feature_item.feature.title).ar"></span>
                                @endif
                                <small v-if="cartMaster.coin_id == 334" class="badge badge-primary badge-pill mx-1 font-weight-normal"
                                v-html="feature_item.price"></small>.
                                <small v-else class="badge badge-primary badge-pill mx-1 font-weight-normal" v-html="feature_item.price_usd"></small>

                                <template v-if="cartMaster.coin_id == 334 && Features(cart.cart_features, feature_item.id, feature_item.price, cart.id) == true">
                                    <small class="font-weight-bold badge badge-warning badge-pill mx-1"> @{{Features_price(cart.cart_features, feature_item.id, feature_item.price, cart.id)}}</small>
                                </template>
                                <template v-if="cartMaster.coin_id == 335 && Features(cart.cart_features, feature_item.id, feature_item.price_usd, cart.id) == true">
                                    <small class="font-weight-bold badge badge-warning badge-pill mx-1"> @{{Features_price(cart.cart_features, feature_item.id, feature_item.price_usd, cart.id)}}</small>
                                </template>
                                <small class="text-danger" v-html=" JSON.parse(feature_item.excerpt).en" v-if="feature_item.feature_id == 5"></small>
                            </span>
                        </label>

                        {{--<li v-for="feature_item in (cart.training_option.training_option_feature)">
                            <template v-if="cartMaster.coin_id == 334 && Features(cart.cart_features, feature_item.id, feature_item.price, cart.id) == true">
                                <span class="text-success font-weight-bold"><i class="far fa-check-circle main-color"></i> In Cart</span>
                                <span class="text-success font-weight-bold"> (@{{Features_price(cart.cart_features, feature_item.id, feature_item.price, cart.id)}})</span>
                            </template>
                            <template v-if="cartMaster.coin_id == 335 && Features(cart.cart_features, feature_item.id, feature_item.price_usd, cart.id) == true">
                                <span class="text-success font-weight-bold">In Cart</span>
                                <span class="text-success font-weight-bold"> (@{{Features_price(cart.cart_features, feature_item.id, feature_item.price_usd, cart.id)}})</span>
                            </template>
                        </li>--}}
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-striped table-bordered table-condensed table-total-info">
            <tbody>
                <tr>
                    <td>Registered Date</td>
                </tr>
                <tr>
                    <td><small class="text-secondary">@{{cart.registered_at??cart.created_at}}</small></td>
                </tr>
                <tr>
                    <td class="title">
                        <template v-if="cart.payment_status != null">
                            <select class="form-control" v-model="cart.payment_status" v-if="moode" @change="changeCartPaymentStatus_forCalc(cart.id,cart.payment_status,$event)">
                                <option v-for="ps in p_status" :value="ps.id">@{{ JSON.parse(ps.name).en ?? null}}</option>
                            </select>
                        </template>

                        <template v-else>
                            <span class="text-white col-sm-6" :class="classNameChange(cart.payment_status)" v-text="changeCartPaymentStatus(cart.payment_status)"></span>
                        </template>
                        <span class="text-white col-sm-6" :class="classNameChange(cart.payment_status)" v-text="changeCartPaymentStatus(cart.payment_status)"></span>
                    </td>
                </tr>
                <tr v-if="moode">
                    <td class="title">
                        <span class="text-danger">Refund Amount (with VAT)</span>
                            <span class="d-block">
                                <input type="number" class="form-control w-75 d-inline mr-1" v-model="cart.refund_value_after_vat"><strong class="text-danger" v-text="converJson(cart.coin.name).en"></strong>
                            </span>
                    </td>
                </tr>
                <template v-if="cert_lms(cart.payment_status)">
                    @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 ||
                        auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174 ||
                        auth()->user()->id==10559 || auth()->user()->id==7952 || auth()->user()->id==13094 )
                        <tr>
                            <td class="author">
                                <template v-if="cart.course">
                                    <template v-if="cart.course.certificate_type_id == 325">
                                        <a :href="'{{url('certificates/attendance')}}/'  + cart.id" class="btn btn-info btn-xs mb-1" target="_blank">
                                            Letter Of Attendance
                                        </a>
                                    </template>
                                    <template v-else>
                                        <a :href="'{{url('certificates/certificate')}}/'  + cart.id" class="btn btn-success btn-xs mb-1" target="_blank">
                                            Certificate
                                        </a>
                                    </template>
                                    <template v-if="cart.certificate_sent_at">
                                        <small class="text-success d-block">Sent before at @{{cart.certificate_sent_at}}</small>
                                    </template>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            {{-- LMS --}}
                            <td class="author">
                                <div>
                                    <template v-if="cart.training_option">
                                        <template v-if="cart.training_option.lms_course_id > 0">
                                            <a :href="'{{url('training/carts')}}/' + cart.id + '/lms'" class="btn btn-warning btn-xs mb-1" target="_blank">
                                                Send LMS
                                            </a>
                                        </template>
                                        <template v-if="cart.training_option.lms_course_id > 0 && cartMaster.type_id == 370">
                                            <a :href="'{{url('training/carts')}}/' + cart.id + '/lms/?send_email=no'" class="btn btn-warning btn-xs mb-1" target="_blank">
                                                Create LMS Account
                                            </a>
                                        </template>
                                        <template v-if="cart.lms_sent_at">
                                            <small class="text-success d-block">LMS was sent before at @{{cart.lms_sent_at}}</small>
                                        </template>
                                        <template v-if="cart.evaluation_sent_at">
                                            <small class="text-success d-block">Evaluation was sent before at @{{cart.evaluation_sent_at}}</small>
                                        </template>
                                    </template>

                                </div>
                            </td>
                        </tr>

                    @endif
                </template>

                <template v-if="cart.course">
                    <template v-if="cart.course.partner_id == 12">
                    {{-- Watson Martin (WM) --}}
                        <tr>
                            <td class="author">
                                <a :href="'{{url('sessions')}}/' + cart.id + '/cipd'" class="btn btn-info btn-xs mb-1" target="_blank"><i class="fa fa-eye" aria-hidden="true" style="font-size: 14px;"></i> Show CIPD Filled Form</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="author">
                                <a :href="'{{url('sessions')}}/' + cart.id + '/exportCipdToDoc'" class="btn btn-dark btn-xs mb-1" target="_blank"><i class="fa fa-download" aria-hidden="true" style="font-size: 14px;"></i> Download CIPD Filled Form</a>
                            </td>
                        </tr>
                    </template>
                </template>
                <template v-if="is_transferred_to(cart.payment_status)">
                    <tr>
                        <td class="">
                            <span>Retrieved Code </span>
                            <span class="d-block">
                              <span class="paid-value badge badge-danger font-weight-normal">@{{cart.retrieved_code}}</span>
                            </span>

                        </td>
                    </tr>
                    <tr v-if="moode">
                        <td class="">
                            <span>Retrieved Amount</span>
                            <span class="d-block">
                                <input type="number" class="form-control w-75 d-inline mr-1" v-model="cart.retrieved_value"><strong class="text-primary" v-text="converJson(cart.coin.name).en"></strong>
                            </span>

                            <button v-if="! cart_flags[cart.id]" @click.prevent="update_balance(cart.id)" class="btn btn-success btn-sm my-2"><li class="fa fa-check"></li><span style="font-family: 'Lato-Regular'; font-size: 13px;">  Approve it in Candidate's balance</span></button>

                            {{-- <button @click.prevent="update_balance(cart.id, cart.retrieved_code, cart.retrieved_value, cart.payment_status)" class="btn btn-success btn-sm my-2"><li class="fa fa-check"></li><span style="font-family: 'Lato-Regular'; font-size: 13px;">  Approve it in Candidate's balance</span></button> --}}
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
