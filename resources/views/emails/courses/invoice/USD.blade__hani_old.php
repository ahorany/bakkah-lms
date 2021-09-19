@extends('emails.courses.master')

@section('content')
<table align="center" style="width: 600px; padding: 5px 20px;padding-bottom:0;" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td style="width: 600px; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;border-bottom:0;">

            <img width="70" src="{{CustomAsset('images/mail/money.jpg')}}" alt="">
            <h1 style="text-align:center;color: #fb4400;font-weight:bold;font-size: 20px;">Invoice</h1>
            <table data-id="customer_data" border="0" cellpadding="0" cellspacing="0" style="width: 100%;margin-top: 15px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;">
                <tbody>
                <tr>
                    <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">From
                    </td>
                    <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                        <h2 style="font-weight: bold;font-size: 15px;color: #737373; line-height: 120%;text-align: center; margin:0;">Bakkah For Learning
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Address
                    </td>
                    <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                        <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">P.O. Box 86593<br>Riyadh 11632, Saudi Arabia
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Name
                    </td>
                    <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                        <h2 style="font-weight: bold;font-size: 16px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{json_decode($user->name)->en??$user->trans_name}}<br>
                            <a style="font-size: 12px;font-weight: normal;" href="mailto:{{$user->email}}">{{$user->email}}</a>
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice Date
                    </td>
                    <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                        <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{date("d-m-Y", strtotime($cart->created_at))}}
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice No.
                    </td>
                    <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                        <h2 style="font-weight: bold;font-size: 15px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cart->invoice_number}}
                        </h2>
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- data-id="customer_data" -->
        </td>
    </tr>

    <tr>
        <td align="center" valign="top" style="background-color: white;border-left: 2px solid #d6d6d6;border-right: 2px solid #d6d6d6;padding: 0px 35px;">
            <table data-id="items_data" cellspacing="0" cellpadding="6" width="600" style="margin-top: 10px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;direction: rtl;" border="0">
                <thead>
                <tr style="text-align:center;color:#737373;">
                <th width="17%" scope="col" style="border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Amount {{(GetCoinId()==335)?'USD':'SAR'}} </th>
                    <th width="14%" scope="col" style="border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Unit Price</th>
                    <th width="12%" scope="col" style="border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Quantity</th>
                    <th width="57%" scope="col" style="border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Description</th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->price)}}</span></td>
                    <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->price)}}</span></td>
                    <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                    <td width="45%" style="text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">
                        {{json_decode($cart->course->title)->en??null}}<br>
                        <br>
                        Delivery Method:{{$cart->trainingOption->constant->en_name}}
                        <br>
                        Date and Time:{{$cart->session->date_from}} - {{$cart->session->date_to}}
                        <br>
                        {!! $cart->session->session_time !!}
                    </td>
                </tr>

                @if($cart->discount_value!=0)
                    <tr>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>-{{NumberFormatWithComma($cart->discount_value)}}</span></td>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{$cart->discount}}%</span></td>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                        <td width="45%" style="text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">Promo Discount <br>{{--Voucher and Paper Based Exam--}}
                        </td>
                    </tr>
                @endif

                @if($cart->exam_price!=0)
                    <tr>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->exam_price)}}</span></td>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->exam_price)}}</span></td>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                        <td width="45%" style="text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">Foundation Exam for {{json_decode($cart->course->title)->en??null}}<br>{{--Voucher and Paper Based Exam--}}
                        </td>
                    </tr>
                @endif

                @if($cart->take2_price!=0)
                    <tr>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->take2_price)}}</span></td>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->take2_price)}}</span></td>
                        <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                        <td width="45%" style="text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">Foundation Exam Take2<br>{{--Voucher and Paper Based Exam--}}
                        </td>
                    </tr>
                @endif

                </tbody>
            </table>
            <!-- data-id="items_data" -->
        </td>
    </tr>


    @include('emails.courses.num_to_words')
<?php
    // include('num_to_words.php');
    $num_str = number_format($cart->total_after_vat,2);
    $decimals = intval(after_num('.', $num_str));
    $integers = intval(before_num('.', $cart->total_after_vat));

    $currency_title_en = $cart->coin_id==335?'U.S. Dollar':'Saudi Riyal';
    $currency_title = $cart->coin_id==335?'دولار أمريكي':'ريال سعودي';
    if($decimals > 0){
        $Total_in_words = number_to_word( $integers ).' and '.$decimals.'/100 '.$currency_title_en;
        $ar_number= new convert_ar($integers, "male");
        $Total_in_words_ar = $ar_number->convert_number().' و  100/'.$decimals.' '.$currency_title;
    }else{
        $Total_in_words = number_to_word( $cart->total_after_vat ).' '.$currency_title_en;
        $ar_number= new convert_ar($cart->total_after_vat, "male");
        $Total_in_words_ar = $ar_number->convert_number().' '.$currency_title;
    }
?>
<tr>
    <td align="center" valign="top" style="background-color: white;border-left: 2px solid #d6d6d6;border-right: 2px solid #d6d6d6;    padding: 0px 35px;">
        <table data-id="items_data_calc" cellspacing="0" cellpadding="6" style="width:100%;margin-top: 0px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;" border="0">
            <tbody>
            <tr>
                <th scope="row" width="30%" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Total ({{(GetCoinId()==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;">
                    <span>{{NumberFormatWithComma($cart->total)}}</span>
                </td>
            </tr>
            @if($cart->vat!=0)
            <tr>
                <th scope="row" width="27%" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Total Value Added Tax {{$cart->vat}}%
                </th>
                <td colspan="2" style="text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;">
                    <span>{{NumberFormatWithComma($cart->vat_value)}}</span>
                </td>
            </tr>
            @endif
            </tbody>
            <tfoot>
            @if($cart->vat!=0)
            <tr>
                <th scope="row" width="27%" style="text-align:left;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Total including Value Added Tax ({{(GetCoinId()==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="text-align:center;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;font-size:15px;">
                    <span>{{NumberFormatWithComma($cart->total_after_vat)}}</span>
                </td>
            </tr>
            @endif
            <tr>
                <th scope="row" width="27%" style="text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">In words ({{(GetCoinId()==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 6px;font-weight: bold;font-size:13px;line-height: 18px;">
                    <span>{!! $Total_in_words !!}</span>
                </td>
            </tr>
            </tfoot>

        </table>
        <!-- data-id="items_data_calc" -->
    </td>
</tr>


    <tr>
        <td align="center" valign="top" style="background-color: white;border: 2px solid #d6d6d6;border-top: 0;padding: 0px 35px;">
            <table data-id="terms_conditions" cellspacing="0" cellpadding="6" style="width:100%;margin-top: 25px;margin-bottom: 15px;padding: 5px;color:#737373;font-size: 12px;" border="0">

                <tbody>
                {{--<tr>
                    <td style="border: 1px solid #e4e4e4;text-align: justify;direction: ltr;vertical-align: top;line-height: 18px;">
                        <b>Our Payment Policies:</b><br>
                        <span style="color: #fb4400;text-align:left;">
                                    - Commercial Register number: 1010061274<br>
                                    - Payment Method: Cheque / Bank Transfer<br>
                                    - Paid For: Bakkah Est.<br>
                                    - IBAN: SA2580000653608010528487<br>
                                    - Bank Name: Al Rajhi Bank<br>
                                    - VAT Account Number: 300806391600003
                                </span>
                    </td>
                </tr>--}}
                <tr>
                    <td width="50%" style="border: 1px solid #e4e4e4;text-align: justify;direction: ltr;vertical-align: top;line-height: 18px;">
                        <b>Terms and Conditions:</b><br>
                        By registering, you hereby declare that you agree to our <a href="{{route('education.static.static-page', ["post_type"=>"terms-and-conditions"])}}" target="_blank">Terms and Conditions</a><br><br>
                        The customer wishing to online training must pay the bill before the training no less than two hours, and in case the payment was before the training less than two hours, we reserve the right to cancel the training or reschedule the training.

                    </td>
                </tr>
                </tbody>
            </table>
            <!-- data-id="terms_conditions"  -->
        </td>
    </tr>

    </tbody>
</table>
@endsection
