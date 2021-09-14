@extends('emails.courses.master')

@section('content')

    <table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear <b>{{$WebinarsRegistration->userId->en_name??null}},</b><br>
                     Hope that you are doing well!</div><br>

                <div>
                    It’s our pleasure to let you know that you have successfully registered to attend “<b>{{$WebinarsRegistration->webinar->en_title??$WebinarsRegistration->webinar->trans_title}}</b>” webinar. So, we would like to welcome you and wish you gain the best and desired knowledge you are looking to get!
                    <br><br>
                    This email is intended to provide you with the needed information to help you attend the Webinar properly.
                    <br><br>
                    Here are the details:<br>
                    <b>Topic:</b> {{$WebinarsRegistration->webinar->en_title??$WebinarsRegistration->webinar->trans_title}}<br>
                    <b>Time:</b> {{$session_time_from}} to {{$session_time_to}} (KSA Time)
                    <br>
                    @if(!is_null($WebinarsRegistration->webinar->zoom_link))
                        <br><b>Join Zoom Meeting:</b> <a href="{{$WebinarsRegistration->webinar->zoom_link}}">{{$WebinarsRegistration->webinar->zoom_link}}</a></b><br>
                    @endif
                    <br>
                    If you face any technical issue and need support, please feel free to contact our Training Department and we’ll get in touch with you in due time!<br>
                    We hope you enjoy the webinar and wish you best of luck!
                    <br><br>

                    <div>Best Regards,</div>
                    <div>Bakkah Inc.</div>
                    <div>Phone: <span dir="ltr">+966 920003928</span></div>
                    <div>Mobile: <span dir="ltr">+966 55 676 5156</span></div>
                    <br>
                </div>
            </td>
        </tr>

    </table>
@endsection
