<style>
    @page{
        margin-top: 270px;
        background: url("https://bakkah.com/public/certificates/img/cert-bg-pmp.png");
        background-position: top center;
        background-repeat: no-repeat;
        background-image-resize: 6;
        background-image-resolution: from-image;
        sheet-size: Letter-L;
    }

    body { font-family: 'arial', sans-serif, serif; }

</style>

<?php
    $training_option = $cart->trainingOption->constant->id??353;
?>
<div class="certificate-content" style="margin: 0 auto;">
    <div style="text-align: center;">
        <h3 style="font-weight: regular;font-size: 26px;color: #000000;">{{$cart->userId->en_name??null}}</h3>

        <br><br><br><br>
        <h3 style="font-weight: regular;font-size: 28px;color: #000000;margin: 0;padding: 5px 70px 0 70px;">
            PMI® Authorized PMP® Exam Prep
        </h3>

            @if($cart->TrainingOption->constant_id == 13)
                <p style="font-weight: regular;font-size: 28px;margin:0;margin-bottom: 15px;margin-top: 10px;color: #000000;">
                    {{$cart->session->pmp_certificate_from??null}}
                </p>
            @endif

            <br>
            <p style="font-weight: regular;font-size: 14px;margin:0;margin-bottom: 15px;margin-top: 80px;color: #000000;">
                Abdelelah Alzaghloul
                {{-- Khalil Hammouri --}}
                {{-- Tamim Al-Qudah --}}
            </p>
    </div>
</div>
