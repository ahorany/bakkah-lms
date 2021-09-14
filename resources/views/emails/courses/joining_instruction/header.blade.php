<table width="600" align="center" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td style="font-size: 12px; vertical-align: middle; font-family:Tahoma,sans-serif;border-bottom: 2px solid #a7a7a7;padding-bottom: 10px;">
            <a href="{{CustomRoute('education.index')}}" target="_blank" title="{{__('education.DC_title')}}">
                <img width="70" src="{{CustomAsset('images/mail/logo-bakkah.png')}}" width="90" border="0" alt="{{__('education.DC_title')}}">
            </a>
        </td>
        <td style="font-size: 12px; vertical-align: bottom; font-family:Tahoma,sans-serif;text-align:right;    border-bottom: 2px solid #a7a7a7;padding-bottom: 10px;">
            <em style="color: #c00000;font-size: 20px;">{{$payment->cart->course->en_title}}</em><br>
            <b style="color: #7e7e7e;font-size: 20px;">Joining Instruction</b>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 12px; vertical-align: bottom; font-family:Tahoma,sans-serif;text-align:right; color:#585858">
            <span>Date: {{ $payment->cart->created_at->isoFormat('dddd, MMM D, Y') }}</span>
{{--            <span>Date: Sunday, July 28, 2019</span>--}}
        </td>
    </tr>
    </tbody>
</table>
