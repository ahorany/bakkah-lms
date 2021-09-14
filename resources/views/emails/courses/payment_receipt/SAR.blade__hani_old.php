@extends('emails.courses.master')

@section('content')
<table align="center" style="width: 600px; padding: 5px 20px;padding-bottom:0;" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td style="width: 600px; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;border-bottom:0;">

            <img width="70" src="{{CustomAsset('images/mail/money.jpg')}}" alt="">
            <h1 style="text-align:center;color: #fb4400;font-weight:bold;font-size: 20px;">Payment Receipt</h1>
            <h1 style="text-align:center;color: #fb4400;font-weight:bold;font-size: 20px;">سند قبض</h1>
            <table data-id="customer_data" border="0" cellpadding="0" cellspacing="0" style="width: 600px;margin-top: 15px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;">
                <tbody>
                <tr>
                    <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">From
                    </td>
                    <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                        <h2 style="font-weight: bold;font-size: 13px;color: #737373;line-height: 120%;text-align: center; margin:0;">Bakkah For Training<br>
                            مركز بكة للتدريب
                        </h2>
                    </td>
                    <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;"> من
                    </td>
                </tr>
                <tr>
                    <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">To
                    </td>
                    <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                        <h2 style="font-weight: bold;font-size: 15px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{json_decode($user->name)->en??$user->trans_name}}<br>
                            <a style="font-size: 12px;font-weight: normal;" href="mailto:{{$user->email}}">{{$user->email}}</a>
                        </h2>
                    </td>
                    <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">إلى
                    </td>
                </tr>
                <tr>
                    <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice No.
                    </td>
                    <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                        <h2 style="font-weight: bold;font-size: 15px;color: #737373;line-height: 120%;text-align: center; margin:0;">{{$cart->invoice_number}}
                        </h2>
                    </td>
                    <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">رقم الفاتورة
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- data-id="customer_data" -->
        </td>
    </tr>

    @include('emails.courses.body')

    @include('emails.courses.footer')

    <tr>
        <td align="center" valign="top" style="background-color: white;border: 2px solid #d6d6d6;border-top: 0;padding: 0px 35px;">
            <table data-id="items_data_stamp" cellspacing="0" cellpadding="0" width="600" style="margin: 30px 0;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;" border="0">
                <tbody>
                <tr>
                    <td width="60">
                        <img width="50" src="{{CustomAsset('images/mail/money.jpg')}}" alt="">
                    </td>
                    <td>
                        <p style="margin-bottom: 0;margin-top: 0;"><strong>NOTICE:</strong><br>
                            Payment sent to: <span style="color:#505050;"><a href="{{CustomRoute('education.index')}}" target="_blank" style="text-decoration:none;color:#fb4400">{{__('education.app_title')}}</a></span><br><span style="font-size: smaller;color: #fb4400;text-align:left;">VAT Account Number: 300806391600003</span></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;margin-top: 30px;background-color: white;">
                        <div style="text-align: center;margin-top: 30px;background-color: white;">
                            <img src="{{CustomAsset('images/mail/Paid-stamp.jpg')}}" alt="discount" height="auto" width="150">
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>
            <!-- data-id="cont_invoice_600" main table 100 width -->
        </td>
    </tr>
    </tbody>
</table>
@endsection
