@if(isset($eloquent))
    <div class="card col-md-12 mt-2 pb-3">
        @include('emails.courses.num_to_words')
        <?php
            $num_str = number_format($total_after_vat,2);
            $decimals = intval(after_num('.', $num_str));
            $integers = intval(before_num('.', $total_after_vat));

            $currency_title_en = (GetCoinId()==335)?'U.S. Dollar':'Saudi Riyal';
            $currency_title = (GetCoinId()==335)?'دولار أمريكي':'ريال سعودي';
            if($decimals > 0){
                $Total_in_words = number_to_word( $integers ).' and '.$decimals.'/100 '.$currency_title_en;
                $ar_number= new convert_ar($integers, "male");
                $Total_in_words_ar = $ar_number->convert_number().' و  100/'.$decimals.' '.$currency_title;
            }else{
                $Total_in_words = number_to_word( $total_after_vat ).' '.$currency_title_en;
                $ar_number= new convert_ar($total_after_vat, "male");
                $Total_in_words_ar = $ar_number->convert_number().' '.$currency_title;
            }
        ?>

        <div class="card-header">
            <h5 class="mb-0 float-left mt-2" style="color: #fb4400;"><i class="far fa-money-bill-alt"></i> {{__('education.Totals')}}</h5>
        </div>

        <div class="card-body p-0">
            {{--  data-id="items_data_calc" cellspacing="0" cellpadding="6" width="600" style="margin-top: 0px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;" border="0" --}}
            <table class="table table-striped table-hover table-total-info">
                <tbody>
                <tr>
                    <th scope="row" width="27%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;font-size: 16px !important;">Subtotal ({{(GetCoinId()==335)?'USD':'SAR'}})
                    </th>
                    <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;font-size: 16px !important;">
                        <span>{{NumberFormatWithComma($total)}}</span>
                    </td>
                    <th scope="row" width="28%" style="font-family: lato;text-align:right;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:rtl;font-size: 16px !important;">إجمالي المبلغ غير شامل ضريبة القيمة المضافة ({{(GetCoinId()==335)?'دولار أمريكي':'ريال سعودي'}})
                    </th>
                </tr>
                <tr>
                    <th scope="row" width="27%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;font-size: 16px !important;">Total Value Added Tax {{$vat}}%
                    </th>
                    <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;font-size: 16px !important;">
                        <span>{{NumberFormatWithComma($vat_value)}}</span>
                    </td>
                    <th scope="row" width="28%" style="font-family: lato;text-align:right;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:rtl;font-size: 16px !important;">ضريبة القيمة المضافة {{$vat}}%
                    </th>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th scope="row" width="29%" style="font-family: lato;text-align:left;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;font-size: 16px !important;">Total including Value Added Tax ({{(GetCoinId()==335)?'USD':'SAR'}})
                    </th>
                    <td colspan="2" style="font-family: lato;text-align:center;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;font-weight: bold;font-size:15px;font-size: 20px !important;">
                        <span>{{NumberFormatWithComma($total_after_vat)}}</span>
                    </td>
                    <th scope="row" width="27%" style="font-family: lato;text-align:right;color:#fb4400;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:rtl;font-size: 16px !important;">الإجمالي شامل ضريبة القيمة المضافة ({{(GetCoinId()==335)?'دولار أمريكي':'ريال سعودي'}})
                    </th>
                </tr>
                <tr>
                    <th scope="row" width="26%" style="font-family: lato;text-align:left;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:ltr;font-size: 16px !important;">In words ({{(GetCoinId()==335)?'USD':'SAR'}})
                    </th>
                    <td colspan="2" style="font-family: lato;text-align:center;color:#737373;border:1px solid #e4e4e4;padding:4px 6px;font-weight: bold;font-size:13px;line-height: 18px;font-size: 16px !important;">
                        <span>{!! $Total_in_words !!}<br>{!! $Total_in_words_ar !!}</span>
                    </td>
                    <th scope="row" width="28%" style="font-family: lato;text-align:right;color:#737373;border:1px solid #e4e4e4;padding:4px 12px;line-height: 18px;direction:rtl;font-size: 16px !important;">المبلغ كتابةً ({{(GetCoinId()==335)?'دولار أمريكي':'ريال سعودي'}})
                    </th>
                </tr>
                </tfoot>

            </table>
        </div>
    </div>
@endif
