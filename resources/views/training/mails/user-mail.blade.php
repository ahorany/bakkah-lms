@include('training.mails.layouts.header')

<main style="text-align: left; width: 90%; margin: 15px auto 0;">
    <div style="margin: 15px 0">
        <h5 style="font-size: 20px; margin: 15px 0; text-align: left;">Dear {{$user->en_name}},</h5>
        <p style="font-size: 16px; margin: 15px 0; text-align: left;">Welcome to Bakkah LMS! We are pleased you joined our community and wish you a great learning experience with us.</p>
        <p style="font-size: 16px; text-align: left;">Through this email, we will guide you in clear steps how to log in to your LMS account, but first you need to take into consideration the following:</p>
    </div>
    <div style="margin: 15px 0; background: #f8f8f8; border-radius: 5px;">
        <table border="0" style="width: 100%; padding: 25px 15px;" cellspacing="0">
            <tr style="text-align: left;">
                <td width="60">
                    <div>
                        <img src="{{CustomAsset('images/email/d2.png')}}" style="vertical-align: bottom;" width="50" height="auto">
                    </div>
                </td>
                <td>
                    <div>
                        <span>You are on <strong>Self-Paced</strong> system in which you can study the materials according to your availability</span>
                    </div>
                </td>
            </tr>
        </table>
        <table border="0" style="width: 100%; padding: 0px 15px 25px;" cellspacing="0">
            <tr style="text-align: left;">
                <td width="60">
                    <div>
                        <img src="{{CustomAsset('images/email/d1.png')}}" style="vertical-align: bottom;" width="50" height="auto">
                    </div>
                </td>
                <td>
                    <div>
                        <span>The course material will be fully in <strong>English</strong></span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div style="width: 100%; margin: 15px 0;">
        <h3 style="color: #000; font-size: 20px; text-align: left;">To log in to your course, please use the following data:</h3>
        <table border="0" style="width: 100%; padding: 25px 15px;" cellspacing="0">
            <tr style="line-height: 1.5; text-align: left;">
                <td>
                    <div width="30">
                        <img src="{{CustomAsset('images/email/user_icon.png')}}" width="25" height="auto">
                    </div>
                </td>
                <td>
                    <span style="font-size: 16px">{{$user->email}}</span>
                </td>
            </tr>
        </table>

        @if (!is_Null($password))
            <table border="0" style="width: 100%; padding: 0 15px 25px;" cellspacing="0">
                    <tr style="line-height: 1.5; text-align: left;">
                        <td>
                            <div width="30">
                                <img src="{{CustomAsset('images/email/email_icon.png')}}" width="25" height="auto">
                            </div>
                        </td>
                        <td>
                            <span style="font-size: 16px;">{{$password}}</span>
                        </td>
                    </tr>
            </table>
        @endif
        <table border="0" style="width: 100%; padding: 0 15px 25px;" cellspacing="0">
            <tr style="line-height: 1.5; text-align: left;">
                <td>
                    <div width="30">
                        <img src="{{CustomAsset('images/email/link_icon.png')}}" width="25" height="auto">
                    </div>
                </td>
                <td>
                    <span style="font-size: 16px;"><a href="{{env('APP_URL')}}" style="text-decoration: none;">{{env('APP_URL')}}</a></span>
                </td>
            </tr>
        </table>
    </div>
    <p style="font-size: 16px; margin: 15px 0; text-align: left;">Feel free to contact us if you have further questions or face any technical issue, and we’ll get touch with you in no time.</p>
    <p style="font-size: 16px; color: #fff; background: #fb4400; text-align: center; line-height: 1.5; border-radius: 5px; padding: 10px;">Happy Learning!</p>
</main>

@include('training.mails.layouts.footer')
