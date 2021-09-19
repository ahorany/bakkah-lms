<style>
    @page{
        /* margin-top: 340px; */
        background: url("https://bakkah.com/public/certificates/img/Letter-of-course-new-bg.png");
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

<?php $gender = 'he';
    if($cart->userId->gender_id != 43){
        $gender = 'she';
    }
    $training_option = $cart->trainingOption->constant->id??353;
?>

<div class="attendance-content" style="margin: 0 auto;">
    <div style="padding: 0 90px;">
        <b><h4 style="margin:0;padding-top: 300px;font-size: 30px;text-transform: uppercase">TO WHOM IT MAY CONCERN,</h4></b>
        <h6 style="background-color: #fb4400;color: #fff;margin: 0;margin-top: 20px;padding: 5px 10px;width: 160px;text-align: center;">{{date_format(now(), 'l, F d, Y')}}</h6>
        <h6 style="margin: 0;padding: 5px 3px;color: #808080;margin-top: 5px;width: 150px;text-align: center;">Riyadh- Saudi Arabia</h6>

        <p style="margin-top: 70px;font-size: 18px;font-weight: 300;position: relative;text-align: justify;color: #4e4e4e;">This is to confirm that <b><span style="font-weight: 600;">{{$cart->userId->trans_name??null}}</span></b> has successfully completed
            @if($cart->trainingOption->PDUs != 0)
                <b><span style="font-weight: 600;">{{$cart->trainingOption->PDUs}}</span></b> hours in
            @endif
                <b><span style="font-weight: 600;">{{$cart->course->ar_disclaimer??$cart->course->en_title}} {{($training_option !=353)?$cart->trainingOption->constant->trans_name : null}}</span></b> with {{__('education.app_title')}}.

            <br><br>
            During the training period, {{$gender}} has shown great adherence and diligence in every session. Additionally, {{$gender}} has completed all the training course requirements successfully.

            <br><br>
            ‘This is a letter confirming course attendance only and is not a document demonstrating or certifying the achievement of any qualification in the subject matter of the training course’.
        </p>

        <div class="row" style="display: inline-block;width: 100%;margin-bottom: 5px;margin-top: 80px;">
            <div style="float: left;">
                <h3 style="margin: 0;padding: 5px 5px;width: fit-content;font-style: italic; margin-top: 5px;">Nawar Saleh Nur</h3>
                <h4 style="margin: 0;padding: 5px 5px;color: #808080;width: fit-content;font-style: italic;">Education Services Director</h4>
                <img src="https://bakkah.com/public/certificates/img/Nawar-Signature.png" style="width: 200px;display: block;margin: 10px auto;margin-bottom: 5px;">
            </div>
        </div>

    </div>
</div>
