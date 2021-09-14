@extends('emails.courses.retarget_master')

@section('content')
<table align="center" style="width: 600px; padding: 0px 20px;padding-bottom:0;" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td style="width: 600px; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px">
            <div style="text-align: center;margin-top: -100px;margin-bottom: 30px;">
                @if($cart->retarget_email_id == 361)
                    <img width="200" src="{{CustomAsset('images/mail/retarget_email_reminder.png')}}" alt="">
                @else
                    <img width="200" src="{{CustomAsset('images/mail/retarget_email_discount.png')}}" alt="">
                @endif
            </div>

            <h2 style="text-align:center;font-weight:bold;font-size: 20px;color: #141414;">Dear <b>{{ $cart->userId->en_name??null }}</b></h2>

            <h1 style="text-align:center;font-weight:bold;font-size: 40px;text-transform: uppercase;color: #141414;margin: 35px 0;">Don't miss the chance</h1>

            @if($cart->retarget_discount > $cart->discount)
                {{-- font-weight:bold; --}}
                <div style="text-align:center;font-size: 20px;color: #141414;">
                    @include('emails.courses.retarget_emails.with_discount', [
                        'training_name'=>$cart->course->training_name??null,
                    ])
                </div>
            @else
                {{-- font-weight:bold; --}}
                <div style="text-align:center;font-size: 20px;color: #141414;">
                    @include('emails.courses.retarget_emails.without_discount', [
                        'training_name'=>$cart->course->training_name??null,
                    ])
                </div>
            @endif

            {{-- <h2 style="text-align:center;font-weight:bold;font-size: 20px;color: #141414;">to take P3O® Course | Online Training. Enroll now via the below link:</h2> --}}

            <div style="text-align: center;margin-top: 40px">
                <a style="text-decoration: none;background-color: #fb4400;color: #fff;padding: 10px 30px;font-size: 24px;border-radius: 5px;display:inline-block;width:150px "
                href="{{route('education.courses.single', ['slug'=>$cart->course->slug])}}{{$cart->course->option__post_type == 'self-study' ? '/'.$cart->course->option__post_type : ''}}">Enroll Now</a>
            </div>
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="background-color: white;padding: 0px 35px;">
            <table data-id="items_data_stamp" cellspacing="0" cellpadding="0" width="600" style="margin: 40px 0 30px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;" border="0">
                <tbody>
                <tr>
                    <td>
                        <a href="{{FACEBOOK}}" target="_blank"><img width="22" style="padding: 3px" src="{{CustomAsset('images/mail/facebook.png')}}" class="CToWUd" alt="facebook"></a>
                        <a href="{{TWITTER}}" target="_blank"><img width="22" style="padding: 3px" src="{{CustomAsset('images/mail/twitter.png')}}" class="CToWUd" alt="twitter"></a>
                        <a href="{{INSTAGRAM}}" target="_blank"><img width="22" style="padding: 3px" src="{{CustomAsset('images/mail/instagram.png')}}" class="CToWUd" alt="instagram"></a>
                        <a href="{{LINKEDIN}}" target="_blank"><img width="22" style="padding: 3px" src="{{CustomAsset('images/mail/linkedin.png')}}" class="CToWUd" alt="linkedin"></a>

                        <p style="font-size:14px;color:#333333; font-family:sans-serif;margin: 10px 0;">
                            <a href="tel:920003928" style="color:#333333;text-decoration:none">Phone: 920003928</a>
                        </p>
                        <p style="font-size:14px;color:#333333; font-family:sans-serif;margin: 10px 0;">
                            <a href="{{route('education.static.static-page', ["post_type"=>"privacy-policy"])}}" style="color:#333333;text-decoration:none">Privacy Policy</a> | <a href="{{route('education.static.contactusIndex')}}" style="color:#333333;text-decoration:none">Contact Support</a>
                        </p>
                        <p style="font-family:sans-serif;font-size:14px;color:#333333;margin: 10px 0;">
                            © {{YEAR}} <a href="{{CustomRoute('education.index')}}" target="_blank" style="color:#fb4400;text-decoration:none;">{{__('education.app_title')}}</a>
                        </p>
                    </td>

                    <td style="text-align: right;">
                        <img src="{{CustomAsset('images/mail/logo-bakkah.png')}}" width="60" alt="Bakkah Learning">
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
@endsection
