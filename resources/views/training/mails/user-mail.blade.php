<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300,700" rel="stylesheet" type="text/css">
<style>
    body {
        background-color: #f6f6f6;
    }
    .default_fs p{
        font-size: 12px;
    }
</style>
<table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
    <tr>
        <td style="direction: ltr; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
            <div>
                <br>
                <div>Dear <b>{{$user->en_name}},</b></div>

                <p>Your password is: {{$password}}</p>

                <div>Best Regards,</div>
                <div>{{__('education.app_title')}}</div>
                {{-- <div>Phone: 920003928</div>
                <div>Mobile: <span dir="ltr">+966 55 676 5156</span></div> --}}
                <br>
            </div>
        </td>
    </tr>
</table>
    {{-- {{$user}} --}}
</div>