<table class="table table-striped table-bordered table-hover table-condensed table-total-info mb-2">
    @forelse($cartMaster->carts as $cart)
        @if($cartMaster->type_id != 374)
            <tr><td colspan="2"></td></tr>
            <tr>
                <td class="value pt-3"><strong>{{$loop->index+1}}) {{$cart->userId->trans_name??null}}</strong></td>
                <td class="value pt-3"><strong>{{$cart->userId->email??null}}</strong></td>
            </tr>
        @endif
            <tr>
                <td class="value">
                    <strong>{{$cart->session->trainingOption->training_name??null}}</strong>
                </td>
                <td class="value">CID: {{$cart->id??null}}</td>
            </tr>

            <tr>
                <td colspan="2" width="100%">
                    <table class="table table-striped table-bordered table-hover table-condensed table-total-info">
                        <tr>
                            <td class="title">Date</td>
                            <td class="value text-secondary">
                                <span style="font-size: 13px;">
                                {{$cart->session->published_from??null}} - {{$cart->session->published_to??null}}</span>
                            </td>
                            <td class="value text-secondary">SID: {{$cart->session->id??null}}</td>
                            <td class="title">Invoice No.</td>
                            <td class="value">{{$cart->invoice_number??null}}</td>
                        </tr>

                        @if(!is_null($cart->group_invoice_id))
                            <tr>
                                <td class="title" colspan="3"><span class="badge badge-danger">Group</span></td>
                                <td class="value" colspan="2"><span class="badge badge-secondary">MID: {{$cart->group_invoice_id??null}}</span></td>
                            </tr>
                        @endif
                        @foreach($cart->cartFeatures as $cartFeature)
                            <tr>
                                <td class="value" colspan="5">
                                    <span class="text-success d-block" style="font-size: 14px;">
                                        {{$loop->index+1}}) {{$cartFeature->feature->title??null}}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        {{-- aaaa{{$cartMaster->paymentStatus->id}} --}}
                        @if(isset($cartMaster->paymentStatus->id))
                            @if(($cartMaster->paymentStatus->id==317 ||
                                $cartMaster->paymentStatus->id==315 || $cartMaster->paymentStatus->id==68 || $cartMaster->paymentStatus->id==376) ||
                                $cartMaster->payment_status==332)

                                @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174)

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
                                                <small class="text-success d-block">Sent before at {{$cart->certificate_sent_at}}</small>
                                            @endif
                                        </td>
                                        {{-- LMS --}}
                                        <td class="author" colspan="3">
                                            @if(!is_null($cart->trainingOption->lms_course_id??0))
                                                @if((auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174))
                                                    <a href="{{ route('training.lms', $cart->id) }}" class="btn btn-warning btn-xs mb-1" target="_blank">Send LMS</a>
                                                @endif
                                            @endif
                                            @if($cart->lms_sent_at)
                                                <small class="text-success d-block">LMS was sent before at {{$cart->lms_sent_at}}</small>
                                            @endif
                                            @if($cart->evaluation_sent_at)
                                                <small class="text-success d-block">Evaluation was sent before at {{$cart->evaluation_sent_at}}</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endif

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
    @empty
        Trainee doen't have any course in this order.
    @endforelse
</table>
