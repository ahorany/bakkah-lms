@include('training.mails.layouts.header')

<main style="text-align: left; width: 90%; margin: 15px auto 0;">
    <div style="margin: 15px 0">
        <h5 style="font-size: 20px; margin: 15px 0; text-align: left;">Dear {{$user->en_name}},</h5>
        <p style="font-size: 16px; margin: 15px 0; text-align: left;">Congrats! You're officially a Bakkah learner.</p>
        <p style="font-size: 16px; text-align: left;">Thank you for enrolling in {{$course->trans_title}} Course.</p>
    </div>
    <div style="width: 100%; margin: 15px 0;">
        <h3 style="color: #000; font-size: 20px; text-align: left;">Please find all the needed instructions and materials by clicking this link:</h3>
        <table border="0" style="width: 100%; padding: 0 15px 25px;" cellspacing="0">
            <tr style="line-height: 1.5; text-align: left;">
                <td width="30">
                    <div>
                        <img src="{{CustomAsset('images/email/link_icon.png')}}" width="25" height="auto">
                    </div>
                </td>
                <td>
                    <span style="font-size: 16px;"><a href="{{env('APP_URL')}}" style="text-decoration: none;">{{env('APP_URL')}}</a></span>
                </td>
            </tr>
        </table>
    </div>
    <p style="font-size: 16px; margin: 15px 0; text-align: left;">Can't wait to hear the big news about you getting certified!</p>
    <p style="font-size: 16px; color: #fff; background: #fb4400; text-align: center; line-height: 1.5; border-radius: 5px; padding: 10px;">Best of luck!</p>
</main>

@include('training.mails.layouts.footer')
