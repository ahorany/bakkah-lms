@extends('training.mails.master')

@section('content')
<table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
            <div>
                <br>
                <div>Dear <b>{{$recieve->trans_name}},</b></div>

                <p>From: {{$message->user->email}}</p>
                <p>The Message is: {{$message->title}}</p>

                <div>Best Regards,</div>
                <div>{{__('education.app_title')}}</div>
                <br>
            </div>
        </td>
    </tr>
</table>

@endsection
