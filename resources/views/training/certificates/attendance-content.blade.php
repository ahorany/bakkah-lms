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
<body style="">
  <div style="position: relative;width: 800px;height:1132px;">

    <div style="height: 100%;background-color: #ffffff;background: url(https://wp.bakkah.net.sa/wp-content/themes/bakkah-new/includes/generate_ji_emails/vendor/phpoffice/phpword/gen_files/certificate/Letter-of-course-new-bg.png) no-repeat;background-size: cover;">

      <div style="text-align: center;">
        <h2 style="font-size: 80px;line-height: 62px;color: #fb4400;margin: 0;padding-top: 330px;">LETTER</h2>
        <h4 style="margin:0;padding-top: 30px;font-size: 30px;font-weight: 300;text-transform: uppercase">of course attendance</h4>
      <h3 style="font-weight: bold;font-size: 30px;position: relative;min-height: 24px;margin-top:70px">{{$post->userId->trans_name??null}}</h3>
      <p style="margin-bottom: 0px;font-size: 22px;font-weight: 300;position: relative;">has successfully completed
        @if($post->course->PDUs != 0)
        {{$post->course->PDUs}} contact hours in
        @endif
      </p>
      <h5 style="font-size: 22px;margin: 0;padding-top: 20px;position: relative;min-height: 33px;">{{$post->trainingOption->training_name??null}}</h5>
      <p style="margin:0;margin-bottom: 15px;margin-top: 15px;font-size: 20px;font-weight: 300;min-height: 19px;margin-bottom:30px">{{$post->session->published_from??null}} - {{$post->session->published_to??null}}</p>

      <div class="row" style="display: inline-block;width: 100%;margin-bottom: 5px;margin-top: 20px;">
      <p style="margin: 50px 0 70px;font-size: 14px;padding: 0 90px;min-height: 24px;font-weight: 400;">‘This is a letter confirming course attendance only and is not a document demonstrating or certifying the achievement of any qualification in the subject matter of the training course.’</p>
        <div style="float: left;padding-left: 15px;position: relative;min-height: 80px;">
          <div style="font-size: 6px;padding-top: 140px;position: relative;">2020100639</div></div>
        <div style="float: right;padding-right: 45px;">
          <img src="https://wp.bakkah.net.sa/wp-content/themes/bakkah-new/includes/generate_ji_emails/vendor/phpoffice/phpword/gen_files/certificate/stamp.png" style="width: 110px;display: block;margin: 0 auto;margin-bottom: 5px;">
        </div>
      </div>

      </div>
    </div>
  </div>
</body>
</html>
