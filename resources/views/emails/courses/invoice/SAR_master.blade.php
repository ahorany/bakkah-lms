@extends('emails.courses.master')

@section('content')
    <table align="center" style="width: 600px; padding: 5px 20px;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td style="width: 600px; background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;border-bottom:0;">

                <img width="70" src="{{CustomAsset('images/mail/money.jpg')}}" alt="">
                <h1 style="text-align:center;color: #fb4400;font-weight:bold;font-size: 20px;">Invoice</h1>
                <h1 style="text-align:center;color: #fb4400;font-weight:bold;font-size: 20px;"> فاتورة </h1>
                <table data-id="customer_data" border="0" cellpadding="0" cellspacing="0" style="width: 600px;margin-top: 15px;padding:5px;border-radius: 0; color:#737373;border-bottom:0;line-height:100%;vertical-align:middle;font-size: 13px;">
                    <tbody>
                    <tr>
                        <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">From
                        </td>
                        <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                            <h2 style="font-weight: bold;font-size: 15px;color: #737373; line-height: 120%;text-align: center; margin:0;">Bakkah For Training<br>
                                مركز بكة للتدريب
                            </h2>
                        </td>
                        <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;"> من
                        </td>
                    </tr>
                    <tr>
                        <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Address
                        </td>
                        <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                            <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">P.O. Box 86593<br>Riyadh 11632, Saudi Arabia<br>ص.ب. 86593، الرياض 11632<br>المملكة العربية السعودية
                            </h2>
                        </td>
                        <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">العنوان
                        </td>
                    </tr>

                    @if($type_id != 374)

                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Name
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 16px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->rfpGroup->organization->en_title??null}}
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">اسم العميل
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Address
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->rfpGroup->organization->address??null}}
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">عنوان العميل
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice Date
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{date("d-m-Y", strtotime($cartMaster->due_date))}}
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">تاريخ الفاتورة
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice No.
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->invoice_number??null}}
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">رقم الفاتورة
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Reference
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->reference??null}}
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">رقم التعميد
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Value Added Tax Number
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->tax_number??null}}
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">الرقم الضريبي للعميل
                            </td>
                        </tr>

                    @else

                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Customer Name
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 16px;color: #737373; line-height: 120%;text-align: center; margin:0;">
                                    {{json_decode($cartMaster->userId->name)->en??$cartMaster->userId->trans_name}}<br>
                                    <a style="font-size: 12px;font-weight: normal;" href="mailto:{{$cartMaster->userId->email}}">{{$cartMaster->userId->email??null}}</a>
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">اسم العميل
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice Date
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 13px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{date("d-m-Y")}}
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">تاريخ الفاتورة
                            </td>
                        </tr>
                        <tr>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:left;border: 1px solid #e4e4e4;">Invoice No.
                            </td>
                            <td style="width:50%;padding:5px 5px;border: 1px solid #e4e4e4;">
                                <h2 style="font-weight: bold;font-size: 15px;color: #737373; line-height: 120%;text-align: center; margin:0;">{{$cartMaster->invoice_number??null}}
                                </h2>
                            </td>
                            <td style="width:25%;padding:5px 10px;color:#737373;font-size: 13px;font-weight: bold;line-height:150%;text-align:right;border: 1px solid #e4e4e4;">رقم الفاتورة
                            </td>
                        </tr>

                    @endif

                    </tbody>
                </table>
                <!-- data-id="customer_data" -->
            </td>
        </tr>

        @include('emails.courses.invoice_body')

        @include('emails.courses.footer')

        <tr>
            <td align="center" valign="top" style="font-family: lato;background-color: white;border: 2px solid #d6d6d6;border-top: 0;padding: 0px 35px;">
                <table data-id="terms_conditions" cellspacing="0" cellpadding="6" width="600" style="font-family: lato;margin-top: 25px;margin-bottom: 15px;padding: 5px;color:#737373;font-size: 12px;" border="0">

                    <tbody>
                    <tr>
                        <td style="font-family: lato;border: 1px solid #e4e4e4;text-align: justify;direction: ltr;vertical-align: top;line-height: 18px;">
                            <b>Our Payment Policies:</b><br>
                            <span style="font-family: lato;color: #fb4400;text-align:left;">
                                        - Commercial Register number: 1010061274<br>
                                        - Payment Method: Cheque / Bank Transfer<br>
                                        - Paid For: Bakkah Est.<br>
                                        - IBAN: SA2580000653608010528487<br>
                                        - Bank Name: Al Rajhi Bank<br>
                                        - VAT Account Number: 300806391600003
                                    </span>
                        </td>
                        <td style="font-family: lato;border: 1px solid #e4e4e4;text-align: right;direction: rtl;font-size: 13px;vertical-align: top;line-height: 18px;">
                            <b>شروط الدفع:</b><br>
                            <span style="font-family: lato;color: #fb4400;text-align:right;">
                                        - سجل تجاري رقم 1010061274<br>
                                        - طريقة الدفع: شيك / تحويل بنكي<br>
                                        - تدفع إلى: مؤسسة بكة للتجارة<br>
                                        <span style="font-family: lato;float: right;direction: rtl;">- الآيبان:&nbsp;</span> SA2580000653608010528487<br>
                                        - اسم البنك: مصرف الراجحي<br>
                                        - رقم تسجيل ضريبة القيمة المضافة: 300806391600003
                                    </span>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" style="font-family: lato;border: 1px solid #e4e4e4;text-align: justify;direction: ltr;vertical-align: top;line-height: 18px;">
                            <b>Terms and Conditions:</b><br>
                            By registering, you hereby declare that you agree to our <a href="{{route('education.static.static-page', ["post_type"=>"terms-and-conditions"])}}" target="_blank">Terms and Conditions</a><br><br>
                            The customer wishing to live online training must pay the bill before the training no less than two hours, and in case the payment was before the training less than two hours, we reserve the right to cancel the training or reschedule the training.

                        </td>
                        <td width="50%" style="font-family: lato;border: 1px solid #e4e4e4;text-align: justify;direction: rtl;font-size: 13px;vertical-align: top">
                            <b>الشروط والأحكام:</b><br>
                            بتسجیلك معنا فإنك تعلن اطّلاعك وموافقتك على<br> <a href="{{LaravelLocalization::getLocalizedURL('ar', route('education.static.static-page', ["post_type"=>"terms-and-conditions"]), [], true)}}" target="_blank">الشروط والأحكام</a><br><br>
                            <span style="font-family: lato;line-height: 25px;">يجب على العميل الراغب فى التدريب عن بعد سداد الفاتورة قبل التدريب بما لايقل عن ساعتين، وفى حال السداد قبل التدريب بأقل من ساعتين فإننا نحتفظ بحقنا فى إلغاء التدريب أو إعادة جدولة التدريب.</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!-- data-id="terms_conditions"  -->
            </td>
        </tr>

        </tbody>
    </table>
    <!-- data-id="cont_invoice_600" main table 100 width -->
@endsection
