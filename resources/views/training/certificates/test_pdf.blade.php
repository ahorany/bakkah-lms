<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Page Title</title>
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
</head>
<style type="text/css">
  body{
    font-family: 'Lato', sans-serif;
    margin: 0;
  }
</style>
<body>
  <div style="position: relative;width:3508px;height:2480px;width: 800px;height:562px;">
    <div style="width: 25%;height: 562px;background-color: #f8f8f8;float:left;border-bottom: 5px solid #4e4e4e;position:relative;">
      <div style="text-align: center;position: relative;height: 100%">
        <div style="font-size: 14px;padding-top: 15px;"><strong>Certificate No. | </strong>2020100641</div>
        <img src="https://wp.bakkah.net.sa/wp-content/themes/bakkah-new/includes/generate_ji_emails/vendor/phpoffice/phpword/gen_files/certificate/logo.png" style="width: 100px;padding-top: 15px;">

        <h3 style="margin: 5px 0 5px 0;padding-top: 115px;">Ramy Adel Zaky Farag</h3>
        <h6 style="margin: 0;margin-bottom: 15px;font-size:8px;">SPHRi™</h6>

        {!! $qr_image !!}
        <img src="https://wp.bakkah.net.sa/wp-content/themes/bakkah-new/includes/generate_ji_emails/vendor/phpoffice/phpword/gen_files/certificate/info.png" style="width: 180px;padding-top: 10px;position: absolute;left: 10px;bottom: 5px;">

      </div>
    </div>
    <div style="width: 75%;height: 562px;background-color: #ffffff;float: left;background: url(https://wp.bakkah.net.sa/wp-content/themes/bakkah-new/includes/generate_ji_emails/vendor/phpoffice/phpword/gen_files/certificate/bg-logo.png) no-repeat;background-size: 130px;background-position: right top;border-bottom: 5px solid var(--mainColor);position: relative;">
      <div style="text-align: center;">
        <h2 style="font-size: 62px;line-height: 62px;color: var(--mainColor);margin: 0;padding-top: 85px;">CERTIFICATE</h2>
        <h4 style="margin:0;padding-top: 15px;font-size: 20px;font-weight: 300;">OF COMPLETION</h4>
      <h3 style="font-weight: bold;font-size: 30px;">{{$cart->userId->trans_name??null}}</h3>
      <p style="margin-bottom: 0px;font-size: 22px;font-weight: 300;">has successfully completed
        @if($cart->course->PDUs != 0)
        {{$cart->course->PDUs}} contact hours in
        @endif
      </p>
      <h5 style="font-size: 22px;margin: 0;padding-top: 10px;">{{$cart->trainingOption->training_name??null}}</h5>
      <p style="margin:0;margin-bottom: 15px;margin-top: 15px;font-size: 20px;font-weight: 300;">{{$cart->session->published_from??null}} - {{$cart->session->published_to??null}}</p>

      <div class="row" style="display: inline-block;width: 100%;margin-bottom: 5px;margin-top: 20px;">
        <div style="float: left;padding-left: 45px;">
        {{-- <img src="{{CustomAsset(env('APP_ASSET').'/upload/thumb300/'.$cart->course->upload->file)}}" style="width: 170px;display: block;margin: 0 auto;margin-top: 10px"> --}}
      </div>
        <div style="float: right;padding-right: 45px;">
          {{-- <img src="https://wp.bakkah.net.sa/wp-content/themes/bakkah-new/includes/generate_ji_emails/vendor/phpoffice/phpword/gen_files/certificate/stamp.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: 5px;"> --}}
        </div>
      </div>
      <div class="row" style="padding-bottom: 5px;position: absolute;bottom: 0px;left:0;display: inline-block;width: 100%;">
        <p style="margin: 0;font-size: 6px;padding: 0 40px;">{{$cart->course->partner->trans_excerpt}}</p>
      </div>
      </div>
    </div>
  </div>
</body>
</html>
