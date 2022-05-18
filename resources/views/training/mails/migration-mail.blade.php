@extends('training.mails.layouts.master')

@section('content')
<main style="text-align: center; width: 90%; margin: 15px auto 0;">

    <div style="margin: 15px 0">
        <h5 style="font-size: 15px; margin: 15px 0; text-align: center;">Dear Candidate,</h5>
        <p style="font-size: 14px; margin: 15px 0; text-align: center;">Greeting from Bakkah Learning!</p>
    </div>

    <table border="0"  width="400" style="margin: 0 auto;" cellspacing="0">
        <tr>
            <td>
                <hr>
            </td>
        </tr>
    </table>

    <p style="font-size: 14px; text-align: left;">
        We hope you receive this email in good health. We are always keen to offer the most suited learning products to improve and polish your skills, inspire growth and build capabilities. In this journey that we venture together. We are pleased to announce that we will be moving to our new Learning Management System (LMS). Due of this moving, you will not be able to login into your accounts on Sunday 15-05-2022, this will resume by the next day. Upon completion of the transfer, you will be able to login to the new Learning Management System (LMS) by using your new username and the new password that you will receive via emails.
        <br>
        <br>
        Note: We will transfer all your training history to date over to the new Learning Management System (LMS).
    </p>

    <table border="0"  width="400" style="margin: 0 auto;" cellspacing="0">-
        <tr>
            <td>
                <hr>
            </td>
        </tr>
    </table>

    <div style="margin: 15px 0;">
        <h3 style="color: #000; font-size: 15px; text-align: center;">New LMS Website</h3>
        <div style="text-align: center; margin: 0 auto;">
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

    <p style="font-size: 14px; text-align: left;">
        Please do not hesitate to contact us directly to gladly assist. Good luck!
        <br><br><br>
        Best regards,
        <br>
        Bakkah Learning Team
    </p>

</main>

@endsection

