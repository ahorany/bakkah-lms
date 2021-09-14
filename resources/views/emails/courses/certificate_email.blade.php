@extends('emails.courses.master')

@section('content')

    <table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear <b>{{$cart->userId->en_name}},</b></div><br>
                <div>Greetings from {{__('education.app_title')}} and hope this email finds you well.<br />

                @if($cart->session_id == 1325)

                    <br>
                    First of all, we would like to thank you for attending the free live online training session "<b>Design Thinking and Creativity for Innovation | 27 June 2021</b>". Based on that, with pleasure, you can find your <b>Attendance Certificate</b> in the attachments.<br /><br />

                    The offers are yet to come! <span style="color: #fb4400;">It’s attached to you</span>. Share your certificate through your LinkedIn and don’t miss the chance to get a free seat in one of our live online CAPM sessions as well.<br /><br />

                    Be around. We are delighted to launch soon more of our free informative courses to provide you the enjoyable knowledge you are looking for!<br />
                    Thank you very much.<br /><br />

                @else
                    @if($subject=='Webinar Certificate')
                    Thank you for attending the Agile Project Management course with {{__('education.app_title')}}, Attached is your e-certificate ready to download.<br /><br />

                    With 40 courses to date, Bakkah Learning offers globally recognized courses such as PMI, Axelos, HRCI, IIBA, DMI, Quality Management, APICS and Watson. So, if you need advice on which options are available to you to further advance your career, we will be more than happy to help! Once again, thank you for your trust in us!

                    @else
                    Thank you for attending the Agile Project Management webinar with {{__('education.app_title')}} Attached is your e-certificate ready to download<br /><br />

                        With 40 courses to date, {{__('education.app_title')}} offers globally recognized courses such as PMI, Axelos, HRCI, IIBA, DMI, Quality Management, APICS and Watson. So, if you need advice on which options are available to you to further advance your career, we will be more than happy to help!<br />

                        {{--<div style="text-align:center;">
                            <a href="{{$pdf}}" title="Bakkah Inc." target="_blank" style="color: #00a63f; text-decoration: none; background-color: #fb4400; border-top: 8px solid #fb4400; border-bottom: 8px solid #fb4400; border-left: 8px solid #fb4400; border-right: 8px solid #fb4400; color:  white; font-weight:  bold; padding:  0 12px;">Download Certificate</a>
                        </div>--}}
                    @endif
                @endif

                    Once again, thank you for your trust in us!<br /><br />
                    <div>Best Regards,</div>
                    <div>{{__('education.app_title')}}</div>
                    <div>Phone: +966 920003928</div>
                    <div>WhatsApp: +966 55 676 5156</div><br><br><br>
                </div>
            </td>
        </tr>

    </table>
@endsection
