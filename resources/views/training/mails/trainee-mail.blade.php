@extends('training.mails.layouts.master')

@section('content')
<main style="text-align: center; width: 90%; margin: 15px auto 0;">

    <div style="margin: 15px 0">
        <h5 style="font-size: 15px; margin: 15px 0; text-align: center;">Dear {{$user->en_name}},</h5>
        <p style="font-size: 14px; margin: 15px 0; text-align: center;">Congrats! You’re officially a Bakkah learner.</p>
        <p style="font-size: 14px; margin: 15px 0; text-align: center;">Thank you for enrolling in <span style="font-size: 16px; font-family: 700;"> <strong>{{$course->trans_title}}</strong> </span> Course.</p>
    </div>

    <table border="0"  width="400" style="margin: 0 auto;" cellspacing="0">
        <tr>
            <td>
                <hr>
            </td>
        </tr>
    </table>

    <div style="margin: 15px 0;">
        <h3 style="color: #000; font-size: 15px; text-align: center;">Please find all the needed instructions and materials by clicking this link:</h3>
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

    <p style="font-size: 14px; text-align: center;">Can’t wait to hear the big news about you getting certified!</p>
    <div width="100" style="margin: 0 auto 15px;">
        <table style="font-size: 14px; color: #fff; background: var(--mainColor); text-align: center; border-radius: 5px; padding: 2px 5px; margin: 0 auto;">
            <tr>
                <td>
                    <span> Best of luck! </span>
                </td>
            </tr>
        </table>
    </div>
</main>

@endsection
