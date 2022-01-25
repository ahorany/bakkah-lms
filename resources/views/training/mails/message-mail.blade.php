@include('training.mails.layouts.header')

<main style="text-align: center; width: 90%; margin: 15px auto 0;">
    <div style="margin: 15px 0">
        <h5 style="font-size: 20px; margin: 15px 0;">Dear {{$recieve->trans_name??null}},</h5>
        <p style="font-size: 24px; font-weight: bold; margin: 15px 0; text-transform: uppercase; color: #fb4400;">Welcome onboard!</p>
        <p style="font-size: 16px; margin: 15px 0;">You have registered in LMS. We would like to welcome you and wish you a great learning experience!</p>
        <hr style="width: 40%; margin: 15px auto;">
        <p style="font-size: 16px;">This email is intended to provide you with the needed information before the beginning of the course to help you log in our LMS system and deal with the course properly.</p>
    </div>

    <div style="margin: 15px 0; background: #f8f8f8; border-radius: 5px;">
        <table border="0" style="margin: 0 auto; width: 100%; padding: 25px 0;" cellspacing="0">
            <tr style="line-height: 0.5; text-align: center;">
                <td colspan="2">
                    <div>
                        <h3 style="font-size: 20px;">Details you need to note</h3>
                    </div>
                </td>
            </tr>
            <tr style="line-height: 2; text-align: center;">
                <td>
                    <div>
                        <img src="{{CustomAsset('images/email/d2.png')}}" style="vertical-align: bottom;" width="50" height="auto">
                        <span style="font-size: 18px">Self Study</span>
                    </div>
                </td>
                <td>
                    <div>
                        <img src="{{CustomAsset('images/email/d1.png')}}" style="vertical-align: bottom;" width="50" height="auto">
                        <span style="font-size: 18px">English</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin: 15px 0;">
        <h3 style="font-size: 20px;">The Learning Management System (LMS)</h3>
        <p style="font-size: 16px;">The access below is for our LMS system where you’ll find the course and all supporting materials!</p>
    </div>

    <hr style="width: 40%; margin: 15px auto;">

    <div style="width: 100%; margin: 15px auto;">
        <h3 style="color: #000; font-size: 20px;">Here’s your details:</h3>
        <table border="0" style="margin: 0 auto; width: 100%;" cellspacing="0">
            <tr style="line-height: 1.5; text-align: center;">
                <td colspan="2">
                    <div style="width: 80%; margin: 5px auto;">
                        <span style="margin-right: 5px;">
                            <img src="{{CustomAsset('images/email/user_icon.png')}}" width="25" height="auto">
                        </span>
                        <span style="font-size: 16px">{{$message_content->user->email}}</span>
                    </div>
                </td>
            </tr>
            {{-- <tr style="line-height: 1.5; text-align: center;">
                <td colspan="2">
                    <div style="width: 80%; margin: 5px auto;">
                        <span style="margin-right: 5px;">
                            <img src="{{CustomAsset('images/email/link_icon.png')}}" width="25" height="auto">
                        </span>
                        <span style="font-size: 16px;"><a href="{{env('APP_URL')}}" style="text-decoration: none;">{{env('APP_URL')}}</a></span>
                    </div>
                </td>
            </tr> --}}
            <tr style="line-height: 1.5; text-align: center;">
                <td colspan="2">
                    <div style="width: 80%; margin: 5px auto;">
                        <h4 style="font-size: 20px;">{{$message_content->title}}</h4>
                        <p style="font-size: 16px;">{{$message_content->description}}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <hr style="width: 40%; margin: 15px auto;">

    <p style="font-size: 16px; margin: 15px 0;">If you face any technical issues and need support, please feel free to contact our Training Department and we’ll get in touch with you in due time! </p>

    <p style="font-size: 16px; color: #fff; background: #fb4400; text-align: center; line-height: 1.5; border-radius: 5px; padding: 10px;">We hope you enjoy this learning experience and wish you best of luck! </p>
</main>

@include('training.mails.layouts.footer')
