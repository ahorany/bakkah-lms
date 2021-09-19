<ul style="overflow-x: auto; min-height:100px; max-height:300px;">
    @foreach($cartMaster->carts as $cart)
        @if($cartMaster->type_id != 374)
            <div class="d-flex justify-content-between d-block">
                <span  class="title">{{__('admin.name')}}: <strong>{{$cart->userId->trans_name??null}}</strong></span>
                <span  class="title">{{__('admin.email')}}: <strong>{{$cart->userId->email??null}}</strong></span>
            </div>
        @endif
        <li style="border-bottom: 19x solid #dddcdc">
            {{-- {{$cart->id??null}} -  --}}
            <span class="text-secondary" style="font-size: 16px;"><strong>{{$cart->session->trainingOption->training_name??null}}</strong></span>
            <span style="color: #999; font-size: 12px;">
                {{$cart->session->published_from??null}} - {{$cart->session->published_to??null}}
                <span class="badge badge-info">SID: {{$cart->session->id??null}}</span>
                <span style="color: #999; font-size: 12px;"> - Inv.: {{$cart->invoice_number??null}}</span>
                @if(!is_null($cart->group_invoice_id))
                    <span class="badge badge-danger">Group</span>
                    <span class="badge badge-secondary">MID: {{$cart->group_invoice_id??null}}</span>
                @endif
                @foreach($cart->cartFeatures as $cartFeature)
                    <span class="text-success d-block" style="font-size: 16px;">{{$cartFeature->feature->title??null}}</span>
                @endforeach
            </span>

            <div class="author">
                @if(isset($cartMaster->payment->payment_status))
                    @if(($cartMaster->payment->payment_status==317 ||
                         $cartMaster->payment->payment_status==315 || $cartMaster->payment->payment_status==68 || $cartMaster->payment->payment_status==376) ||
                         $cartMaster->payment_status==332)
                        <div class="d-flex justify-content-between d-block">
                            <div>
                                @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 ||
                                auth()->user()->id==5337 || auth()->user()->id==6174)

                                    @if (isset($cart->course->certificate_type_id) && $cart->course->certificate_type_id == 325)
                                        <a href="{{ route('certificates.attendance.index', $cart->id) }}" class="btn btn-link btn-xs" target="_blank">
                                            Letter Of Attendance
                                        </a>
                                    @else
                                        <a href="{{ route('certificates.certificate.index', $cart->id) }}" class="btn btn-link btn-xs" target="_blank">
                                            Certificate
                                        </a>
                                    @endif
                                @endif
                                @if($cart->certificate_sent_at)
                                <small class="text-success" style="display: block; border-bottom: 1px solid #cccccc;">Sent before at {{$cart->certificate_sent_at}}</small>
                                @endif

                                @if(!is_null($cart->trainingOption->lms_course_id??0))
                                    @if((auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174))
                                        <a href="{{ route('training.lms', $cart->id) }}" class="btn btn-link btn-xs" target="_blank">Send LMS</a>
                                    @endif
                                @endif

                                @if($cart->lms_sent_at)
                                    <small class="text-success" style="display: block; border-bottom: 1px solid #cccccc;">LMS was sent before at {{$cart->lms_sent_at}}</small>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                @if($cart->course->partner_id)
                    {{-- Watson Martin (WM) --}}
                    @if($cart->course->partner_id == 12)
                        <a href="{{route('education.courses.question.exportCipdToDoc', $cart->id??0)}}" class="btn btn-xs btn-success" target="_blank"><span>Download CIPD Filled Form</span></a>
                    @endif
                @endif
            </div>
        </li>
    @endforeach
</ul>
