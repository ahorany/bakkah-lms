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
        <p style="margin-bottom: 5px;margin-top: 25px;font-size: 16px;font-weight: 300;color: #707070;">has successfully completed 3 contact hours in
        </p>
        <h5 style="font-size: 20px;color: #5b5b5b;margin: 0;padding: 5px 70px 0 70px;">
            <?php $city_id = $cart->b2b->city_id??'Riyadh'; ?>
            {{$cart->webinar->en_title}} Course
        </h5>

            <p style="margin:0;margin-bottom: 15px;margin-top: 10px;font-size: 16px;font-weight: 300;color: #707070;">
                {{$cart->webinar->certificate_from??null}}
            </p>

            <p style="margin:0;margin-bottom: 15px;margin-top: 20px;font-size: 16px;font-weight: 300;color: #707070;">
                {{--@if($cart->trainingOption->type_id==370)
                    {{$city_id}} - Saudi Arabia
                @else
                    Riyadh - Saudi Arabia
                @endif--}}
                Riyadh - Saudi Arabia
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
                        {{-- @if($cart->course->type_id != 370)
                            @isset($cart->course->upload->file)
                                <img src="https://bakkah.com/public/upload/thumb300/{{$cart->course->upload->file}}" style="width: 200px;display: block;margin: 0 auto;margin-top: 5px;">
                            @endisset
                        @endif --}}
                    </td>
                    <td width="15%"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
