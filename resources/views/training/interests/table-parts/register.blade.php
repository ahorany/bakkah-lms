<table class="table table-striped table-bordered table-hover table-condensed table-total-info">
    <tr>
        <td style="width: 200px;" class="title">{{__('admin.price')}}</td>
        <td style="width: 200px;" class="value">{{$post->price}}</td>
        <td style="width: 200px;" class="title">{{__('admin.vat')}}(<span style="color:#dc3545;">{{$post->vat}}%</span>)</td>
        <td style="width: 200px;" class="value">{{$post->vat_value}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.discount')}}(<span style="color:#28a745;">-{{$post->discount}}%</span>)</td>
        <td class="value">-{{$post->discount_value}}</td>
        <td class="title">{{__('admin.total_after_vat')}}</td>
        <td class="value">{{$post->total_after_vat}}</td>
    </tr>
    <tr>
        <td class="title">{{__('admin.exam_price')}}</td>
        <td class="value">{{$post->exam_price}}</td>
        <td class="title">{{__('admin.paid')}}</td>
        <td class="value">
            @if(isset($post->payment->paid_in) && $post->payment->paid_in!=0)
                {{--<span class="value">{{$post->payment->paid_in??0}}</span><br>--}}
                <span class="paid-value badge badge-info">
                    {{--$post->payment->paymentStatus->trans_name??null--}}
                    {{$post->payment->paid_in??0}}
                </span><br>
            @endif
        </td>
    </tr>
    <tr>
        <td class="title">{{__('admin.take2_price')}}</td>
        <td class="value">{{$post->take2_price}}</td>
        <td class="title">{{__('admin.status')}}</td>
        <td class="value">
            @if(isset($post->payment->paid_in) && $post->payment->paid_in!=0)
                <span class="badge {{($post->payment->payment_status==68)?'badge-success':'badge-danger'}}">
                    {{$post->payment->paymentStatus->trans_name??null}}
                </span><br>
            @endif
        </td>
    </tr>
    <tr>
        <td class="title">{{__('admin.sub-total')}}</td>
        <td class="value">{{$post->total}}</td>
        <td class="title">{{__('admin.date')}}</td>
        <td class="author">{{$post->created_at}}</td>
    </tr>
    <tr>
        <td class="title"></td>
        <td class="value"></td>
        <td class="title"></td>
        <td class="author">

            @if(isset($post->payment->payment_status) && ($post->payment->payment_status==332 || $post->payment->payment_status==317 || $post->payment->payment_status==315 || $post->payment->payment_status==68 ))
                @if(auth()->user()->id==1 || auth()->user()->id==2 || auth()->user()->id==3178 ||
                auth()->user()->id==5337 || auth()->user()->id==1)

                    @if (isset($post->course->certificate_type_id) && $post->course->certificate_type_id == 325)
                        <a href="{{ route('certificates.attendance.index', $post->id) }}" class="btn btn-info btn-xs">
                            Letter Of Attendance
                        </a>
                    @else
                        <a href="{{ route('certificates.certificate.index', $post->id) }}" class="btn btn-success btn-xs">
                            Certificate
                        </a>
                    @endif
                @endif
                @if($post->certificate_sent_at)
                <small class="text-success" style="display: block;">Certificate was sent before at {{$post->certificate_sent_at}}</small>
                @endif

            @endif
            {{--<span class="badge badge-success badge-pill">
                {{__('admin.certificate')}}
            </span>--}}
        </td>
    </tr>
</table>
