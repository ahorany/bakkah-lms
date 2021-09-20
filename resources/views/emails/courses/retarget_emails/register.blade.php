@extends('emails.courses.master')

@section('content')
    <style>
        .default_fs p{font-size: 12px;}
    </style>
    <table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear <b>{{ $cart->userId->en_name??null }},</b></div><br>

                <div>

                    @if(is_null($value) || $value == 0)
                        The registered session date of <b>{{$training_name}}</b> course has expired, you can re-register in another session for the same course via this link:
                    @else
                        @include('emails.courses.retarget_emails.with_discount')
                    @endif

                    {{-- @include('emails.courses.retarget_emails.with_discount', ['cart'=>$cart]) --}}
                    <div style="text-align:center;">
                        @if($value==0)
                            <a href="{{route('epay.checkout', [
                                'cart'=>$cart->id,
                            ])}}" title="{{__('education.app_title')}}" target="_blank" style="color: #00a63f; text-decoration: none; background-color: #fb4400; border-top: 8px solid #fb4400; border-bottom: 8px solid #fb4400; border-left: 8px solid #fb4400; border-right: 8px solid #fb4400; color:  white; font-weight:  bold; padding:  0 12px;">Don't miss out! Enroll NOW!</a>
                        @else
                            <a href="{{route('education.courses.register', [
                                'slug'=>$cart->course->slug,
                                'session_id'=>$cart->session_id,
                                'promo'=>'discount',
                            ])}}" title="{{__('education.app_title')}}" target="_blank" style="color: #00a63f; text-decoration: none; background-color: #fb4400; border-top: 8px solid #fb4400; border-bottom: 8px solid #fb4400; border-left: 8px solid #fb4400; border-right: 8px solid #fb4400; color:  white; font-weight:  bold; padding:  0 12px;">Don't miss out! Enroll NOW!</a>
                        @endif
                    </div>

                    <br>
                        Please register and pay within 24 hours to confirm your new session registration.
                    <br>

                    <div>Best Regards,</div>
                    <div>{{__('education.app_title')}}</div>
                    <div>Phone: 920003928</div>
                    <div>Mobile: <span dir="ltr">+966 55 676 5156</span></div>
                    <br>
                </div>
            </td>
        </tr>

    </table>
@endsection