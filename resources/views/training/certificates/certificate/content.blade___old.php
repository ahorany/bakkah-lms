<div class="certificate-content" style="position: relative;width:3508px;height:2480px;width: 800px;height:562px;">
<div style="width: 25%;height: 562px;background-color: #f8f8f8;float:left;border-bottom: 5px solid #4e4e4e;position:relative;">
    <div style="text-align: center;position: relative;height: 100%">
    <div style="font-size: 14px;padding-top: 15px;"><strong>Certificate No. | </strong>{{$cart->cert_no}}</div>
    <img src="{{CustomAsset('certificates/img/logo.png')}}" style="width: 100px;padding-top: 15px;">

    <h3 style="margin: 5px 0 5px 0;padding-top: 115px;">{{$cart->session->trainer->en_name??null}}</h3>
    <h6 style="margin: 0;margin-bottom: 15px;font-size:8px;">{{$cart->session->trainer->en_trainer_courses_for_certifications??null}}</h6>

    {!! $qr_image !!}
    <img src="{{CustomAsset('/certificates/img/info.png')}}" style="width: 180px;padding-top: 10px;position: absolute;left: 10px;bottom: 5px;">

    </div>
</div>
<div style="width: 75%;height: 562px;background-color: #ffffff;float: left;background: url({{CustomAsset('/certificates/img/bg-logo.png')}}) no-repeat;background-size: 130px;background-position: right top;border-bottom: 5px solid var(--mainColor);position: relative;">
    <div style="text-align: center;">
    <h2 style="font-size: 62px;line-height: 62px;color: var(--mainColor);margin: 0;padding-top: 80px;">CERTIFICATE</h2>
    <h4 style="margin:0;padding-top: 15px;padding-bottom: 40px;font-size: 20px;font-weight: 300;">OF COMPLETION</h4>
    <h3 style="font-weight: bold;font-size: 30px;">{{$cart->userId->en_name??null}}</h3>
    <p style="margin-bottom: 0px;font-size: 22px;font-weight: 300;">has successfully completed
    @if($cart->course->PDUs != 0)
        {{$cart->course->PDUs}} contact hours in
    @endif
    </p>
    {{-- $cart->trainingOption->training_name --}}
    <h5 style="font-size: 22px;margin: 0;padding-top: 10px;">{{$cart->course->ar_disclaimer??null}}</h5>
    <p style="margin:0;margin-bottom: 15px;margin-top: 15px;font-size: 20px;font-weight: 300;">{{$cart->session->certificate_from??null}} - {{$cart->session->certificate_to??null}}</p>

    <div class="row" style="display: inline-block;width: 100%;margin-bottom: 5px;margin-top: 20px;">
    <div style="float: left;padding-left: 45px;">
    <img src="{{CustomAsset('/upload/thumb300/'.$cart->course->upload->file)}}" style="width: 190px;display: block;margin: 0 auto;margin-top: 0;">
    </div>
    <div style="float: right;padding-right: 45px;">
        <img src="{{CustomAsset('/certificates/img/stamp.png')}}" style="width: 100px;display: block;margin: 0 auto;margin-bottom: 5px;">
    </div>
    </div>
    <div class="row" style="padding-bottom: 5px;position: absolute;bottom: 0px;left:0;display: inline-block;width: 100%;">
    <p style="margin: 0;font-size: 6px;padding: 0 40px;">{{$cart->course->en_disclaimer}}</p>
    </div>
    </div>
</div>
</div>
