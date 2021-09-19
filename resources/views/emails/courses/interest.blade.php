@extends('emails.courses.master')

@section('content')
    <style>
        .default_fs p{font-size: 12px;}
    </style>
    <table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear <b style="font-weight: bold;">{{ $CourseInterest->name??null }},</b></div><br>

                <div>
                    {{-- March with Bakkah carries for you exclusive offers for a limited period of time. Take the opportunity and visit our website to learn more and claim your discount now!
                    <br>
                    <p><strong>Looking forward to having you soon</strong></p> --}}
                    Congratulations!<br>
                    Driven by your interest in <b style="font-weight: bold;">PMI-PBA® Course</b>, we are pleased to offer you a promo code discount "<b style="font-weight: bold; color: #fb4400;">PMI-PBA/MAR21</b>" to enroll right away!<br>
                    Register now and get the course <b style="font-weight: bold;">25% off</b>!
                    <br>
                    <br>
                    {{-- <div style="text-align:center;">
                        <a href="{{route('education.hot-deals')}}" title="{{__('education.app_title')}}" target="_blank" style="color: #00a63f; text-decoration: none; background-color: #fb4400; border-top: 8px solid #fb4400; border-bottom: 8px solid #fb4400; border-left: 8px solid #fb4400; border-right: 8px solid #fb4400; color:  white; font-weight:  bold; padding:  0 12px; border-radius: 5px; font-size:18px;">Enroll Now</a>
                    </div> --}}
                    <div style="text-align:center;">
                        <a href="{{route('education.courses.single', ['slug'=>'pmi-pba'])}}" title="{{__('education.app_title')}}" target="_blank" style="color: #00a63f; text-decoration: none; background-color: #fb4400; border-top: 8px solid #fb4400; border-bottom: 8px solid #fb4400; border-left: 8px solid #fb4400; border-right: 8px solid #fb4400; color:  white; font-weight:  bold; padding:  0 12px; border-radius: 5px; font-size:18px;">Enroll Now</a>
                    </div>

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
