<table class="table table-striped table-bordered table-hover table-condensed table-total-info mb-2">
    @forelse($cartMaster->carts as $cart)
        @if($cartMaster->type_id != 374)
            <tr><td colspan="2"></td></tr>
            <tr>
                <td class="value pt-3"><strong>{{$loop->index+1}}) {{$cart->userId->trans_name??null}}</strong></td>
                <td class="value pt-3"><strong>{{$cart->userId->email??null}}</strong></td>
            </tr>
        @endif
        <template v-for="cart in carts">
            <tr>
                <td class="value">
                    <strong>@{{JSON.parse(cart.course.title).en??null}} | @{{JSON.parse(cart.training_option.type.name).en??null}}</strong>
                </td>
                <td class="value text-secondary">Item #: @{{cart.id??null}}</td>
            </tr>

            <tr>
                <td colspan="2" width="100%">
                    <table class="table table-striped table-bordered table-hover table-condensed table-total-info">
                        <tr>
                            <td class="title">Date</td>
                            <td class="value text-secondary">
                                <span style="font-size: 13px;">
                                @{{format(cart.session.date_from)??null}} -  @{{format(cart.session.date_to)??null}}</span>
                            </td>
                            <td class="value text-secondary">SID: @{{cart.session.id??null}}</td>
                            <td class="title">Invoice No.</td>
                            <td class="value text-secondary" style="font-size: 13px;">@{{cart.invoice_number??null}}</td>
                        </tr>

                        <template v-if="cart.group_invoice_id != null">
                            <tr>
                                <td class="title" colspan="3"><span class="badge badge-danger">Group</span></td>
                                <td class="value" colspan="2"><span class="badge badge-secondary">MID: @{{cart.group_invoice_id??null}}</span></td>
                            </tr>
                        </template>

                        <tr>
                            <td class="value pr-2 pl-2" colspan="5">
                                <table class="table table-striped table-bordered table-hover table-condensed table-total-info text-secondary"style="font-size: 13px;">
                                    <tr>
                                        <td style="width: 200px;" class="title">Product Price before VAT	</td>
                                        <td style="width: 100px;" class="value">@{{cart.price}}</td>
                                        <td style="width: 200px;" class="title">Discount(<span style="color:#28a745;">-@{{cart.discount}}%</span>)
                                            <template v-if="cart.promo_code != null">
                                                <br><small class="paid-value badge badge-danger">@{{cart.promo_code}}</small>
                                            </template>
                                            </td>
                                        <td style="width: 100px;" class="value">-@{{cart.discount_value}}</td>

                                        <td style="width: 200px;" class="title">Product Price after VAT</td>
                                        <td style="width: 100px;" class="value">@{{Math.round(cart.price - cart.discount_value)}}</td>
                                    </tr>

                                    <tr>

                                    </tr>
                                    <template v-for="(cartFeature, index) in cart.cart_features">
                                        <tr>
                                            <td class="value">
                                                <span class="text-success d-block" style="font-size: 14px;">
                                                    @{{index+1}}) @{{cartFeature.training_option_feature.feature.title??null}}
                                                </span>
                                            </td>
                                            <td class="value">
                                                <span class="text-success d-block" style="font-size: 14px;">@{{cartFeature.training_option_feature.price??0}}</span>
                                            </td>
                                        </tr>
                                    </template>


                                    <tr>

                                        <td class="title">Net Total (With VAT)</td>
                                        <td class="value">@{{cart.total}}</td>
                                        <td class="title">VAT(<span style="color:#dc3545;">@{{cart.vat}}%</span>)</td>
                                        <td class="value">@{{cart.vat_value}}</td>

                                        <td class="title">Net Total (With VAT)</td>
                                        <td class="value">@{{cart.total_after_vat}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2" class="author text-right">@{{cart.registered_at}}</td>


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
                                        <td class="title">Payment Status</td>
                                        <td class="title">
                                            <template v-if="cart.payment_status != null">
                                                <select class="form-control" v-model="cart.payment_status" v-if="moode">
                                                    <option v-for="ps in p_status" :value="ps.id">@{{ JSON.parse(ps.name).en ?? null}}</option>
                                                </select>
                                            </template>

                                            <template v-else>
                                                <span class="text-white col-sm-6" :class="classNameChange(cart.payment_status)" v-text="changeCartPaymentStatus(cart.payment_status)"></span>
                                            </template>


                                            <span class="text-white col-sm-6" :class="classNameChange(cart.payment_status)" v-text="changeCartPaymentStatus(cart.payment_status)"></span>
                                        </td>

                                    </tr>
                                </table>
                            </td>
                        </tr>



                        <?php
                            $cert_lms = false;
                            if(isset($cartMaster->payment->payment_status)){
                                if($cartMaster->payment->payment_status==317 || $cartMaster->payment->payment_status==315 || $cartMaster->payment->payment_status==68 || $cartMaster->payment->payment_status==376){
                                    $cert_lms = true;
                                }
                            }elseif ($cartMaster->payment_status==332) {
                                $cert_lms = true;
                            }
                        ?>

                            @if($cert_lms)

                                @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174 || auth()->user()->id==10559)

                                    <tr>
                                        <td class="author" colspan="2">
                                            @if (isset($cart->course->certificate_type_id) && $cart->course->certificate_type_id == 325)
                                                <a href="{{ route('certificates.attendance.index', $cart->id) }}" class="btn btn-info btn-xs mb-1" target="_blank">
                                                    Letter Of Attendance
                                                </a>
                                            @else
                                                <a href="{{ route('certificates.certificate.index', $cart->id) }}" class="btn btn-success btn-xs mb-1" target="_blank">
                                                    Certificate
                                                </a>
                                            @endif
                                            @if($cart->certificate_sent_at)
                                                <small class="text-success">Sent before at {{$cart->certificate_sent_at}}</small>
                                            @endif
                                        </td>
                                        {{-- LMS --}}
                                        <td class="author" colspan="3">
                                            <div>
                                                @if(!is_null($cart->trainingOption->lms_course_id??0))
                                                    @if((auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174 || auth()->user()->id==10559))
                                                        <a href="{{ route('training.lms', $cart->id) }}" class="btn btn-warning btn-xs mb-1" target="_blank">Send LMS</a>
                                                    @endif
                                                @endif
                                                @if($cart->lms_sent_at)
                                                    <small class="text-success">LMS was sent before at {{$cart->lms_sent_at}}</small>
                                                @endif
                                                @if($cart->evaluation_sent_at)
                                                    <small class="text-success">Evaluation was sent before at {{$cart->evaluation_sent_at}}</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        {{-- @endif --}}

                        @if(isset($cart->course->partner_id))
                            {{-- Watson Martin (WM) --}}
                            @if($cart->course->partner_id == 12)
                                <tr>
                                    <td class="author" colspan="2">
                                        <a href="{{route('education.courses.question.cipd', $cart->id??0)}}" class="btn btn-info btn-xs mb-1" target="_blank"><i class="fa fa-eye" aria-hidden="true" style="font-size: 14px;"></i> Show CIPD Filled Form</a>
                                    </td>
                                    <td class="author" colspan="3">
                                        <a href="{{route('education.courses.question.exportCipdToDoc', $cart->id??0)}}" class="btn btn-dark btn-xs mb-1" target="_blank"><i class="fa fa-download" aria-hidden="true" style="font-size: 14px;"></i> Download CIPD Filled Form</a>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </td>
            </tr>

        </template>


    @empty
        Trainee doen't have any course in this order.
    @endforelse
</table>
