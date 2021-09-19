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

                    @if($type_id != 374)

                        <tr>
                            <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Name
                            </td>
                            <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 16px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->rfpGroup->organization->en_title??null}}
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Address
                            </td>
                            <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 16px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->rfpGroup->organization->address??null}}
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice Date
                            </td>
                            <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{date("d-m-Y", strtotime($cartMaster->due_date))}}
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice No.
                            </td>
                            <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 15px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->invoice_number??null}}
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Reference
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->reference??null}}
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Value Added Tax Number
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->tax_number??null}}
                                </h2>
                            </td>
                        </tr>

                    @else

                        <tr>
                            <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Name
                            </td>
                            <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 16px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{json_decode($cartMaster->userId->name)->en??$cartMaster->userId->trans_name}}<br>
                                    <a style="font-size: 12px;font-weight: normal;" href="mailto:{{$cartMaster->userId->email}}">{{$cartMaster->userId->email}}</a>
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice Date
                            </td>
                            <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{date("d-m-Y")}}
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:30%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice No.
                            </td>
                            <td style="width:70%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 15px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->invoice_number??null}}
                                </h2>
                            </td>
                        </tr>

                    @endif
                </tbody>
            </table>
            <!-- data-id="customer_data" -->
        </td>
    </tr>

    @include('emails.courses.invoice_body_USD')

    @include('emails.courses.num_to_words')
<?php
    // include('num_to_words.php');
    $num_str = number_format($masterTotals->total_after_vat,2);
    $decimals = intval(after_num('.', $num_str));
    $integers = intval(before_num('.', $masterTotals->total_after_vat));

    $currency_title_en = ($cartMaster->coin_id==335)?'U.S. Dollar':'Saudi Riyal';
    $currency_title = ($cartMaster->coin_id==335)?'دولار أمريكي':'ريال سعودي';

    if($decimals > 0){
        $Total_in_words = number_to_word( $integers ).' and '.$decimals.'/100 '.$currency_title_en;
        $ar_number= new convert_ar($integers, "male");
        $Total_in_words_ar = $ar_number->convert_number().' و  100/'.$decimals.' '.$currency_title;
    }else{
        $Total_in_words = number_to_word( $masterTotals->total_after_vat ).' '.$currency_title_en;
        $ar_number= new convert_ar($masterTotals->total_after_vat, "male");
        $Total_in_words_ar = $ar_number->convert_number().' '.$currency_title;
    }
?>
<tr>
    <td align="center" valign="top" style="background-color: white;border-left: 2px solid #d6d6d6;border-right: 2px solid #d6d6d6;    padding: 0px 35px;">
        <table data-id="items_data_calc" cellspacing="0" cellpadding="6" style="width:100%;margin-top: 0px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;" border="0">
            <tbody>
            <tr>
                <th scope="row" width="30%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Total ({{($cartMaster->coin_id==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;">
                    @if ($cartMaster->coin_id==335)
                        <span>{{NumberFormatWithComma($masterTotals->total_after_vat)}}</span>
                    @else
                        <span>{{NumberFormatWithComma($masterTotals->total)}}</span>
                    @endif

                </td>
            </tr>
            @if($cartMaster->vat!=0)
            <tr>
                <th scope="row" width="27%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Total Value Added Tax {{$cartMaster->vat}}%
                </th>
                <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;">
                    <span>{{NumberFormatWithComma($masterTotals->vat_value)}}</span>
                </td>
            </tr>
            @endif
            </tbody>
            <tfoot>
            @if($cartMaster->vat!=0)
            <tr>
                <th scope="row" width="27%" style="font-family: lato;text-align:left;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Total including Value Added Tax ({{($cartMaster->coin_id==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="font-family: lato;text-align:center;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;font-size:15px;">
                    <span>{{NumberFormatWithComma($masterTotals->total_after_vat)}}</span>
                </td>
            </tr>
            @endif
            <tr>
                <th scope="row" width="27%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">In words ({{($cartMaster->coin_id==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 6px;font-weight: bold;font-size:13px;line-height: 18px;">
                    <span>{!! $Total_in_words !!}</span>
                </td>
            </tr>
            </tfoot>

        </table>
        <!-- data-id="items_data_calc" -->
    </td>
</tr>


    <tr>
        <td align="center" valign="top" style="font-family: lato;background-color: white;border: 2px solid #d6d6d6;border-top: 0;padding: 0px 35px;">
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
                    <td width="50%" style="font-family: lato;border: 1px solid #e4e4e4;text-align: justify;direction: ltr;vertical-align: top;line-height: 18px;">
                        <b>Terms and Conditions:</b><br>
                        By registering, you hereby declare that you agree to our <a href="{{route('education.static.static-page', ["post_type"=>"terms-and-conditions"])}}" target="_blank">Terms and Conditions</a><br><br>
                        The customer wishing to live online training must pay the bill before the training no less than two hours, and in case the payment was before the training less than two hours, we reserve the right to cancel the training or reschedule the training.

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
