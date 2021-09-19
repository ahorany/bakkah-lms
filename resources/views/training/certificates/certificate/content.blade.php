{{-- PMP --}}
@if( $cart->course_id == 1 )
    @include('training.certificates.certificate.pmp')
@else
    <style>
        @page{
            margin-top: 340px;
            background: url("https://bakkah.com/public/certificates/img/cert-bg-min.png");
            background-image-resize:6;
            background-image-resolution: from-image;
        }

        body { font-family: 'lato', sans-serif, serif; }

        .barcode {
            padding: 2.5mm;
            margin: 0;
            vertical-align: top;
            color: #000;
        }
        .barcodecell {
            float: right;
            text-align: center;
            vertical-align: middle;
        }
    </style>
    <?php
        $training_option = $cart->trainingOption->constant->id??353;
    ?>
    <div class="certificate-content" style="margin: 0 auto;">
        <div style="text-align: center;">
            <h3 style="font-style: italic;font-weight: bold;font-size: 30px;color: #5b5b5b;">{{$cart->userId->en_name??null}}</h3>
            <p style="margin-bottom: 5px;margin-top: 25px;font-size: 16px;font-weight: 300;color: #707070;">has successfully completed
                @if($cart->trainingOption->PDUs != 0)
                    {{$cart->trainingOption->PDUs}} contact hours in
                @endif
            </p>
            <h5 style="font-size: 20px;color: #5b5b5b;margin: 0;padding: 5px 70px 0 70px;">
                <?php $city_id = $cart->b2b->city_id??'Riyadh'; ?>
                @if($cart->trainingOption->type_id==370)  {{-- B2B --}}
                    {{$cart->course->ar_disclaimer??$cart->course->en_title}} Live Online Training
                @else
                    {{$cart->course->ar_disclaimer??$cart->course->en_title}} {{($training_option !=353 && $training_option !=383)?$cart->trainingOption->constant->trans_name : null}}
                @endif
            </h5>

                @if($cart->TrainingOption->constant_id == 13 || $cart->TrainingOption->constant_id == 383)
                    <p style="margin:0;margin-bottom: 15px;margin-top: 10px;font-size: 16px;font-weight: 300;color: #707070;">
                        {{$cart->session->certificate_from??null}} - {{$cart->session->certificate_to??null}}
                    </p>
                @endif

                <p style="margin:0;margin-bottom: 15px;margin-top: 20px;font-size: 16px;font-weight: 300;color: #707070;">
                    @if($cart->trainingOption->type_id==370)  {{-- B2B --}}
                        {{$city_id}} - Saudi Arabia
                    @else
                        Riyadh - Saudi Arabia
                    @endif
                </p>

            <div class="row" style="margin-top: 20px;">
                <br><br><br><br>
                <table width="80%" align="center" cellpadding="0">
                    <tr>
                        <td width="15%" align="left">
                            <div class="barcodecell">
                                <barcode code="{!! $data_for_qr !!}" type="QR" class="barcode" size="0.9" error="L" disableborder="I" />
                            </div>
                        </td>
                        <td width="65%" align="center">
                            @if($cart->course->type_id != 370)
                                @isset($cart->course->upload->file)
                                    <img src="https://bakkah.com/public/upload/thumb300/{{$cart->course->upload->file}}" style="width: 200px;display: block;margin: 0 auto;margin-top: 5px;">
                                @endisset
                            @endif
                        </td>
                        <td width="15%"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endif
