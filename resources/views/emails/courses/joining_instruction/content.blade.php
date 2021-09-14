<table style="font-family: Tahoma,sans-serif;font-size:14px;color:#585858" width="600" align="center" cellspacing="0" cellpadding="0">
    <thead>
    <tr>
        <th style="background:#BFBFBF;color:#404040;padding:5px;width: 150px;">Title</th>
        <th style="background:#BFBFBF;color:#404040;">Details</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="background:#F2F2F2;padding: 10px;border: 1px solid #d4d4d4">Date(s) & Time </td>
        <td style="padding:10px;border: 1px solid #d4d4d4">
            <span style="width: 120px;display: inline-block;margin-bottom: 4px;">Start Date: </span> <em style="color:#c00000">{{ \Carbon\Carbon::parse($payment->cart->session->date_from)->isoFormat('dddd, MMM D, Y') }}</em><br>
            <span style="width: 120px;display: inline-block;margin-bottom: 4px;">Duration: </span> <span>{{$payment->cart->session->duration}} {{$payment->cart->session->durationType->en_name}} </span><br>
            {{--<span style="width: 120px;display: inline-block;margin-bottom: 4px;">Start time: </span> <span>4:30pm </span><br>
            <span style="width: 120px;display: inline-block;margin-bottom: 4px;">End time: </span> <span>10:30pm </span><br>--}}
            {!! $payment->cart->session->session_time!!}
        </td>
    </tr>
    {{--<tr>
        <td style="background:#F2F2F2;padding: 10px;border: 1px solid #d4d4d4">Venue </td>
        <td style="padding:10px;border: 1px solid #d4d4d4">
            <span style="color:#c00000;display: inline-block;margin-bottom: 4px;">Riyadh Chamber</span><br>
            <a style="color:#c00000;display: inline-block;margin-bottom: 4px;" href="https://goo.gl/maps/6M3Q8z3TgmH2">https://goo.gl/maps/6M3Q8z3TgmH2</a><br>
            <span>Prince Abdulaziz Ibn Musaid Ibn Jalawi Street</span>

        </td>
    </tr>--}}
    <tr>
        <td style="background:#F2F2F2;padding: 10px;border: 1px solid #d4d4d4">Course Objective </td>
        <td style="padding:10px;border: 1px solid #d4d4d4">
            <p>{!! $payment->cart->course->en_excerpt !!}</p>

        </td>
    </tr>
    <tr>
        <td style="background:#F2F2F2;padding: 10px;border: 1px solid #d4d4d4">Delivery Method </td>
        <td style="padding:10px;border: 1px solid #d4d4d4">
            <p>{!! $payment->cart->trainingOption->constant->en_name !!}</p>
{{--            <p>Instructor-led, 3-Days intensive aPHRi Course </p>--}}
        </td>
    </tr>
    <tr>
        <td style="background:#F2F2F2;padding: 10px;border: 1px solid #d4d4d4">Language</td>
        <td style="padding:10px;border: 1px solid #d4d4d4">
            <p>Material and discussion language will be <span style="color:#c00000">
                    @foreach($payment->cart->course->postMorph()->whereIn('constant_id', [36,37])->get() as $lang)
                        <span class="text-secondary ">{{$lang->constant->trans_name}} {{($loop->index==1)?',':''}}</span>
                    @endforeach
                </span></p>
        </td>
    </tr>
    </tbody>
</table>
<br>
<br>
