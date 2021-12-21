@extends('training.mails.master')

@section('content')
<table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
            <div>
                <br>
                <div style="font-family: -webkit-pictograph;">Dear <b>{{$user->en_name}},</b></div>
                <br>
                @if (!is_Null($user->email))
                    <div>
                        <span style="color: #fb4400; font-family: -webkit-pictograph;"><strong>Username:</strong></span>
                        <span style="font-family: -webkit-pictograph;">{{$user->email}}</span>
                    </div>
                @endif

                @if (!is_Null($password))
                    <div>
                        <span style="color: #fb4400; font-family: -webkit-pictograph;"><strong>Password:</strong></span>
                        <span style="font-family: -webkit-pictograph;">{{$password}}</span>
                    </div>
                @endif

                @if (!is_Null($user->mobile))
                    <div>
                        <span style="color: #fb4400; font-family: -webkit-pictograph;"><strong>Mobile:</strong></span>
                        <span style="font-family: -webkit-pictograph;">{{$user->mobile}}</span>
                    </div>
                @endif

                <br>
                <div>
                    <span style="color: #fb4400; font-family: -webkit-pictograph;"><strong>Website:</strong></span>
                    <span style="font-family: -webkit-pictograph;"><a href="{{env('APP_URL')}}">{{env('APP_URL')}}</a></span>
                </div>
                <br>
                <div style="font-family: -webkit-pictograph;">Best Regards,</div>
                <div style="font-family: -webkit-pictograph;">{{__('education.app_title')}}</div>
                {{-- <div>Phone: 920003928</div>
                <div>Mobile: <span dir="ltr">+966 55 676 5156</span></div> --}}
                <br>
            </div>
        </td>
    </tr>
</table>

@endsection
