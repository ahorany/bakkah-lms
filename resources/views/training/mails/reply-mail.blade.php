@extends('training.mails.layouts.master')

@section('content')
<main style="text-align: center; width: 90%; margin: 15px auto 0;">

    <div style="margin: 15px 0">
        <h5 style="font-size: 15px; margin: 15px 0; text-align: center;">Dear {{$user->en_name}},</h5>
        <p style="font-size: 14px; margin: 15px 0; text-align: center;">{{$message_content->user->en_name}} has replied to your message for the {{$course->trans_title}}, and the reply is the following:</p>
        <p style="font-size: 14px; margin: 15px 0; text-align: center;">{{$message_content->description}}</p>
    </div>

    <table border="0"  width="400" style="margin: 0 auto;" cellspacing="0">
        <tr>
            <td>
                <hr>
            </td>
        </tr>
    </table>

    <div width="100" style="margin: 0 auto 15px;">
        <table style="font-size: 14px; color: #fff; background: #fb4400; text-align: center; border-radius: 5px; padding: 2px 5px; margin: 0 auto;">
            <tr>
                <td>
                    <span> Regards. </span>
                </td>
            </tr>
        </table>
    </div>
</main>

@endsection
