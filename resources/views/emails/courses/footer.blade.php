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
    <td align="center" valign="top" style="background-color: white;border-left: 2px solid #d6d6d6;border-right: 2px solid #d6d6d6;">
        <table data-id="items_data_calc" cellspacing="0" cellpadding="6" width="600" style="margin-top: 0px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;" border="0">
            <tbody>
            <tr>
                <th scope="row" width="27%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Subtotal ({{($cartMaster->coin_id==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;">
                    <span>{{NumberFormatWithComma($masterTotals->total)}}</span>
                </td>
                <th scope="row" width="28%" style="font-family: lato;text-align:right;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:rtl;">إجمالي المبلغ غير شامل ضريبة القيمة المضافة ({{($cartMaster->coin_id==335)?'دولار أمريكي':'ريال سعودي'}})
                </th>
            </tr>
            <tr>
                <th scope="row" width="27%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Total Value Added Tax {{$cartMaster->vat}}%
                </th>
                <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;">
                    <span>{{NumberFormatWithComma($masterTotals->vat_value)}}</span>
                </td>
                <th scope="row" width="28%" style="font-family: lato;text-align:right;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:rtl;">ضريبة القيمة المضافة {{$cartMaster->vat}}%
                </th>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <th scope="row" width="29%" style="font-family: lato;text-align:left;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">Total including Value Added Tax ({{($cartMaster->coin_id==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="font-family: lato;text-align:center;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;font-size:15px;">
                    <span>{{NumberFormatWithComma($masterTotals->total_after_vat)}}</span>
                </td>
                <th scope="row" width="27%" style="font-family: lato;text-align:right;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:rtl;">الإجمالي شامل ضريبة القيمة المضافة ({{($cartMaster->coin_id==335)?'دولار أمريكي':'ريال سعودي'}})
                </th>
            </tr>
            <tr>
                <th scope="row" width="26%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;">In words ({{($cartMaster->coin_id==335)?'USD':'SAR'}})
                </th>
                <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 6px;font-weight: bold;font-size:13px;line-height: 18px;">
                    <span>{!! $Total_in_words !!}<br>{!! $Total_in_words_ar !!}</span>
                </td>
                <th scope="row" width="28%" style="font-family: lato;text-align:right;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:rtl;">المبلغ كتابةً ({{($cartMaster->coin_id==335)?'دولار أمريكي':'ريال سعودي'}})
                </th>
            </tr>
            </tfoot>

        </table>
        <!-- data-id="items_data_calc" -->
    </td>
</tr>
