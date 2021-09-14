<tr>
    <td align="center" valign="top" style="background-color: white;border-left: 2px solid #d6d6d6;border-right: 2px solid #d6d6d6;padding: 0px 35px;">
        <table data-id="items_data" cellspacing="0" cellpadding="6" width="600" style="margin-top: 10px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;" border="0">
            <thead>
            <tr style="text-align:center;color:#737373;">
            <th width="17%" scope="col" style="border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Amount {{(GetCoinId()==335)?'USD':'SAR'}}<br>{{(GetCoinId()==335)?'الإجمالي بالدولار':'الإجمالي بالريال'}} </th>
                <th width="14%" scope="col" style="border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Unit Price<br>الوحدة</th>
                <th width="12%" scope="col" style="border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Quantity<br>الكمية</th>
                <th width="57%" scope="col" style="border:1px solid #e4e4e4;padding:7px;background-color: #fb4400;color:#fff;line-height: 150%;">Description<br>البيان</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->price)}}</span></td>
                <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->price)}}</span></td>
                <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                <td width="45%" style="text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">
                    {{json_decode($cart->course->title)->en??null}}<br>
                    <span dir="rtl">{{json_decode($cart->course->title)->ar??null}}</span>
                    <br><br>
                    Delivery Method: {{$cart->trainingOption->constant->en_name}}
                    {{-- self --}}
                    @if($cart->trainingOption->constant_id != 11)
                        <br>
                        Date and Time: {{$cart->session->date_from}} - {{$cart->session->date_to}}
                        <br>
                        {!! $cart->session->session_time !!}
                        {{--Voucher and Paper Based Exam--}}
                        @if ($cart->session->except_fri_sat == 1)
                            {{__('admin.except_fri_sat')}}
                        @endif
                    @endif
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
                    <td width="45%" style="text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">Foundation Exam for {{json_decode($cart->course->title)->en??null}}<br><span dir="rtl">اختبار تأسيسي {{json_decode($cart->course->title)->ar??null}}</span><br>{{--Voucher and Paper Based Exam--}}
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

            @if($cart->exam_simulation_price!=0)
                <tr>
                    <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->exam_simulation_price)}}</span></td>
                    <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px"><span>{{NumberFormatWithComma($cart->exam_simulation_price)}}</span></td>
                    <td style="text-align:center;vertical-align:middle;border:1px solid #e4e4e4;color:#737373;padding:12px">1</td>
                    <td width="45%" style="text-align:left;vertical-align:middle;border:1px solid #e4e4e4;word-wrap:break-word;color:#737373;padding:12px;line-height: 150%;">Exam Simulations<br>{{--Voucher and Paper Based Exam--}}
                    </td>
                </tr>
            @endif

            </tbody>
        </table>
        <!-- data-id="items_data" -->
    </td>
</tr>
