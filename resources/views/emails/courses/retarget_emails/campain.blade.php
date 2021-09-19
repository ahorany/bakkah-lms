@extends('emails.courses.retarget-master')

@section('content')
    <style>
        .default_fs p{font-size: 12px;}
    </style>
    <table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear,</b></div><br>
                <div>
                    <p>
                        We are inviting you to join us in our new webinar entitled “Roadmap to being a professional in supply chain” with seniors in Supply Chain Management who will guide you the right path.
                        On Thursday 11 Feb.2021 at 8:00pm until 09:30pm via ZOOM
                        Can’t wait to meet you in the webinar!
                    </p>

                    <p style="font-size: 14px; font-weight: bold;">Don't miss out!</p>

                    <div style="text-align:center;">
                        <a href="{{route('education.static.webinars.single', ['slug'=>'roadmap-for-professional-supply-chain'])}}" title="{{__('education.app_title')}}" target="_blank" style="color: #00a63f; text-decoration: none; background-color: #fb4400; border-top: 8px solid #fb4400; border-bottom: 8px solid #fb4400; border-left: 8px solid #fb4400; border-right: 8px solid #fb4400; color:  white; font-weight:  bold; padding:  0 12px;">Enroll NOW FOR FREE!</a>
                    </div>

                    <br>

                    <div>Best Regards,</div>
                    <div>{{__('education.app_title')}}</div>
                    <div>Phone: 920003928</div>
                    <div>Mobile: <span dir="ltr">+966 55 676 5156</span></div>
                    <br>
                    <br>
                </div>
            </td>
            {{--<td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear <b style="font-weight: bold;">{{ $cart->userId->en_name??null }},</b></div><br>
                <div>
                    <p>January discount is about to End!</p>
                </div><br>

                <div>
                    <p>Seize the opportunity and confirm your registration in <b style="font-weight: bold;">({{$cart->course->en_title}})</b> course by completing the payment step via this link!</p>

                    <p style="font-size: 14px; font-weight: bold;">Don't miss out!</p>

                    <div style="text-align:center;">
                        <a href="{{route('education.courses.single', ['slug'=>$cart->course->slug, 'method'=>$cart->trainingOption->type->post_type])}}" title="{{__('education.app_title')}}" target="_blank" style="color: #00a63f; text-decoration: none; background-color: #fb4400; border-top: 8px solid #fb4400; border-bottom: 8px solid #fb4400; border-left: 8px solid #fb4400; border-right: 8px solid #fb4400; color:  white; font-weight:  bold; padding:  0 12px;">Enroll NOW!</a>
                    </div>

                    <br>

                    <div>Best Regards,</div>
                    <div>{{__('education.app_title')}}</div>
                    <div>Phone: 920003928</div>
                    <div>Mobile: <span dir="ltr">+966 55 676 5156</span></div>
                    <br>
                    <br>
                </div>
            </td>
            --}}
        </tr>

    </table>
@endsection
