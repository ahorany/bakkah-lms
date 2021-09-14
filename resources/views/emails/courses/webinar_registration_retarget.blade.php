@extends('emails.courses.master')

@section('content')

    <table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear <b>{{$WebinarsRegistration->userId->en_name??null}},</b><br>
                    <br>
                    Good day!</div>
                <div>
                    Hope you are doing well. This email is to inform you that you have successfully registered to attend “<b>{{$WebinarsRegistration->webinar->en_title??$WebinarsRegistration->webinar->trans_title}}</b>” webinar. Here are the details you need to attend:
                    <br><br>
                    <b>Time:</b>Today, {{$session_time_from}} to {{$session_time_to}} (KSA Time)
                    <br>
                    @if(!is_null($WebinarsRegistration->webinar->zoom_link))
                    <br><b>Join Zoom Meeting:</b> <a href="{{$WebinarsRegistration->webinar->zoom_link}}">{{$WebinarsRegistration->webinar->zoom_link}}</a></b><br>
                    @endif
                    <b>Webinar ID: 910 9951 6048</b>
                    <br>
                    Hope you gain the knowledge you are looking for!
                    <br><br>

                    <div>Best of luck,</div>
                    <div>Bakkah Inc.</div>
                    <div>Phone: <span dir="ltr">+966 920003928</span></div>
                    <div>Mobile: <span dir="ltr">+966 55 676 5156</span></div>
                    <br>
                </div>
            </td>
        </tr>

    </table>
@endsection
