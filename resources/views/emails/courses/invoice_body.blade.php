<tr>
    <td align="center" valign="top" style="background-color: white;border-left: 2px solid #d6d6d6;border-right: 2px solid #d6d6d6;padding: 0px 35px;">
        <table data-id="items_data" cellspacing="0" cellpadding="6" width="600" style="margin-top: 10px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;" border="0">
            <thead>
            <tr style="text-align:center;color:#737373;">
            <th width="17%" scope="col" style="font-family: lato;border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Amount {{(GetCoinId()==335)?'USD':'SAR'}}<br>{{(GetCoinId()==335)?'الإجمالي بالدولار':'الإجمالي بالريال'}} </th>
                <th width="14%" scope="col" style="font-family: lato;border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Unit Price<br>الوحدة</th>
                <th width="12%" scope="col" style="font-family: lato;border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Quantity<br>الكمية</th>
                <th width="57%" scope="col" style="font-family: lato;border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Description<br>البيان</th>
            </tr>
            </thead>
            <tbody>

@if(!is_null($cartMaster))
    @foreach($cartMaster->carts as $cart)
            <tr>
                <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->price)}}</span></td>
                <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->price)}}</span></td>
                <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                <td width="45%" style="font-family: lato;text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">
                    @if($type_id != 374)
                        Candidate Name: {{$cart->userId->en_name??null}} - {{$cart->invoice_number??null}}
                        <br><br>
                    @endif
                    {{json_decode($cart->course->title)->en??null}}<br>
                    <span dir="rtl">{{json_decode($cart->course->title)->ar??null}}</span>
                    <br><br>
                    Delivery Method: {{$cart->trainingOption->constant->en_name??null}}
                    {{-- Online --}}
                    @if($cart->trainingOption->constant_id == 13)
                        <br>
                        Date and Time: {{$cart->session->date_from??null}} - {{$cart->session->date_to??null}}
                        <br>
                        {!! $cart->session->session_time??null !!}
                        {{--Voucher and Paper Based Exam--}}
                        @if ($cart->session->except_fri_sat??0 == 1)
                            {{__('admin.except_fri_sat')}}
                        @endif
                    @endif
                </td>
            </tr>

            @if($cart->discount_value!=0)
                <tr>
                    <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>-{{NumberFormatWithComma($cart->discount_value)}}</span></td>
                    <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{$cart->discount}}%</span></td>
                    <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                    <td width="45%" style="font-family: lato;text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">Promo Discount<br>{{--Voucher and Paper Based Exam--}}
                    </td>
                </tr>
            @endif

            @if(!is_null($cart->cartFeatures))
                @foreach($cart->cartFeatures as $feature)
                    <tr>
                        <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($feature->price)}}</span></td>
                        <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($feature->price)}}</span></td>
                        <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                        <td width="45%" style="font-family: lato;text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">
                            @if ($feature->trainingOptionFeature->feature->id != 5)
                                {{$feature->trainingOptionFeature->feature->trans_title??null}}{{--Voucher and Paper Based Exam--}}
                            @else
                                {{json_decode($feature->trainingOptionFeature->excerpt??'')->en??''}} {{--Book title--}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif

            @if($cart->refund_value_after_vat!=0)
                <tr>
                    <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>-{{NumberFormatWithComma($cart->refund_value_before_vat)}}</span></td>
                    <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>-{{NumberFormatWithComma($cart->refund_value_before_vat)}}</span></td>
                    <td style="font-family: lato;text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                    <td width="45%" style="font-family: lato;text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">Refund Amount<br>{{--Voucher and Paper Based Exam--}}
                    </td>
                </tr>
            @endif

    @endforeach
@endif
            </tbody>
        </table>
        <!-- data-id="items_data" -->
    </td>
</tr>
