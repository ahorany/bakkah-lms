@extends('training.mails.layouts.master')

@section('content')
<main style="text-align: center; width: 90%; margin: 15px auto 0;">

    <div style="margin: 15px 0">
        <h5 style="font-size: 15px; margin: 15px 0; text-align: center;">Dear {{$user->en_name}},</h5>
        <p style="font-size: 14px; margin: 15px 0; text-align: center;">Welcome to Bakkah LMS! We are pleased you joined our community and wish you a great learning experience with us.</p>
        @isset($course)
            @if ($course->training_option_id == 11)
                <p style="font-size: 14px; text-align: center;">Through this email, we will guide you in clear steps how to log in to your LMS account, but first you need to take into consideration the following:</p>
            @endif
        @endisset
    </div>


    <table border="0"  width="400" style="margin: 0 auto;" cellspacing="0">
        <tr>
            <td>
                <hr>
            </td>
        </tr>
    </table>

    <div style="margin: 15px 0;">
        <h3 style="color: #000; font-size: 15px; text-align: center;">To log in to your course, please use the following data:</h3>
        <div style="text-align: center; margin: 0 auto;">
            <table border="0" style="line-height: 2; padding: 0 10px; margin: 0 auto;" cellspacing="0">
                <tr style="text-align: center;">
                    <td width="20">
                        <img src="{{CustomAsset('images/email/user_icon.png')}}" width="16" height="auto">
                    </td>
                    <td width="180" style="text-align: left">
                        <span style="line-height: 2; font-size: 15px;">{{$user->email}}</span>
                    </td>
                </tr>
            </table>
            @if (!is_Null($password))
                <table border="0" style="line-height: 2; padding: 0 10px; margin: 0 auto;" cellspacing="0">
                    <tr style="text-align: center;">
                        <td width="20">
                            <img src="{{CustomAsset('images/email/email_icon.png')}}" width="16" height="auto">
                        </td>
                        <td width="180" style="text-align: left">
                            <span style="line-height: 2; font-size: 15px;">{{$password}}</span>
                        </td>
                    </tr>
                </table>
            @endif
            <table border="0" style="line-height: 2; padding: 0 10px; margin: 0 auto;" cellspacing="0">
                <tr style="text-align: center;">
                    <td width="20">
                        <img src="{{CustomAsset('images/email/link_icon.png')}}" width="16" height="auto">
                    </td>
                    <td width="180" style="text-align: left">
                        <span style="line-height: 2; font-size: 15px;"><a href="{{env('APP_URL')}}" style="text-decoration: none;">{{env('APP_URL')}}</a></span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table border="0"  width="400" style="margin: 0 auto;" cellspacing="0">
        <tr>
            <td>
                <hr>
            </td>
        </tr>
    </table>

    <p style="font-size: 14px; text-align: center;">Feel free to contact us if you have further questions or face any technical issue, and we’ll get touch with you in no time.</p>
    <div width="100" style="margin: 0 auto 15px;">
        <table style="font-size: 14px; color: #fff; background: #fb4400; text-align: center; border-radius: 5px; padding: 2px 5px; margin: 0 auto;">
            <tr>
                <td>
                    <span> Happy Learning! </span>
                </td>
            </tr>
        </table>
    </div>
</main>

@endsection

