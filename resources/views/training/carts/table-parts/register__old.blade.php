<table class="table table-striped table-bordered table-hover table-condensed table-total-info">
    <tr>
        <td style="width: 200px;" class="title">{{__('admin.price')}}</td>
        <td style="width: 200px;" class="value">{{$post->price}}</td>
        <td style="width: 200px;" class="title">{{__('admin.vat')}}(<span style="color:#dc3545;">{{$post->vat}}%</span>)</td>
        <td style="width: 200px;" class="value">{{$post->vat_value}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.discount')}}(<span style="color:#28a745;">-{{$post->discount}}%</span>)
            @if(!is_null($post->promo_code))
                <br><small class="paid-value badge badge-danger">{{$post->promo_code}}</small>
            @endif
            </td>
        <td class="value">-{{$post->discount_value}}</td>
        <td class="title">{{__('admin.total_after_vat')}}</td>
        <td class="value">{{$post->total_after_vat}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.exam_price')}}</td>
        <td class="value">{{$post->exam_price}}</td>
        <td class="title">{{__('admin.paid')}}</td>
        <td class="value">
            @if(isset($post->payment->paid_in))
                <span class="paid-value badge {{($post->coin_id==334)?'badge-info':'badge-warning'}}">
                    {{--$post->payment->paymentStatus->trans_name??null--}}
                    {{($post->payment->paid_in!=0)?$post->payment->paid_in:$post->payment->paid_out}} <small>{{$post->coin->trans_name??null}}</small>
                </span><br>
            @endif
        </td>
    </tr>
    <tr>
        <td class="title">{{__('admin.take2_price')}}</td>
        <td class="value">{{$post->take2_price}}</td>
        <td class="title">{{__('admin.status')}}</td>
        <td class="value">
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
            {{-- @if(isset($post->payment->paid_in))
                <span class="badge {{($post->payment->payment_status==68)?'badge-success':'badge-danger'}}">
                    {{$post->payment->paymentStatus->trans_name??null}}
                </span><br>
            @endif --}}
            @if($post->payment_status)
            <span class="badge badge-{{$class[$post->payment_status]??null}}">
                {{$post->paymentStatus->trans_name??null}}
            </span>
            @endif

        </td>
    </tr>
    <tr>
        <td class="title">{{__('admin.exam_simulation')}}</td>
        <td class="value">{{$post->exam_simulation_price}}</td>
        <td class="title">{{__('admin.date')}}</td>
        <td class="author">{{$post->registered_at}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.sub-total')}}</td>
        <td class="value">{{$post->total}}</td>
        {{-- <td class="title"></td> --}}
        <td colspan="2" class="author">
            @if(isset($post->payment->payment_status))
                @if(($post->payment->payment_status==317 || $post->payment->payment_status==315 || $post->payment->payment_status==68 || $post->payment->payment_status==376)) || $post->payment_status==332)
                    <div class="d-flex justify-content-between">
                        <div>
                            @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 ||
                            auth()->user()->id==5337 || auth()->user()->id==6174)

                                @if (isset($post->course->certificate_type_id) && $post->course->certificate_type_id == 325)
                                    <a href="{{ route('certificates.attendance.index', $post->id) }}" class="btn btn-info btn-xs" target="_blank">
                                        Letter Of Attendance
                                    </a>
                                @else
                                    <a href="{{ route('certificates.certificate.index', $post->id) }}" class="btn btn-success btn-xs" target="_blank">
                                        Certificate
                                    </a>
                                @endif
                            @endif
                            @if($post->certificate_sent_at)
                            <small class="text-success" style="display: block;">Sent before at {{$post->certificate_sent_at}}</small>
                            @endif
                        </div>
                        {{-- LMS --}}
                        <div>
                            @if(!is_null($post->trainingOption->lms_course_id??0))
                                @if((auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3 || auth()->user()->id==3178 || auth()->user()->id==5337 || auth()->user()->id==6174))
                                    <a href="{{ route('training.lms', $post->id) }}" class="btn btn-success btn-xs" target="_blank">Send LMS</a>
                                @endif
                            @endif

                            @if($post->lms_sent_at)
                                <small class="text-success" style="display: block;">LMS was sent before at {{$post->lms_sent_at}}</small>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
            {{--<span class="badge badge-success badge-pill">
                {{__('admin.certificate')}}
            </span>--}}
        </td>
    </tr>
</table>
