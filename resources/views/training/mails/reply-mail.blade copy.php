@include('training.mails.layouts.header')

<main style="text-align: left; width: 90%; margin: 15px auto 0;">
    <div style="margin: 15px 0">
        <h5 style="font-size: 20px; margin: 15px 0; text-align: left;">Dear {{$recieve->en_name}},</h5>
        <p style="font-size: 16px; margin: 15px 0; text-align: left;">{{$message_content->user->en_name}} has replied to your message for the {{$course->trans_title}}, and the reply is the following:</p>
        <p style="font-size: 16px; text-align: left;">{{$message_content->description}}</p>
    </div>
    <p style="font-size: 16px; color: #fff; background: #fb4400; text-align: center; line-height: 1.5; border-radius: 5px; padding: 10px;">Regards.</p>
</main>

@include('training.mails.layouts.footer')
