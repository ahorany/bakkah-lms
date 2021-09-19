<!DOCTYPE html>
<html>
<head></head>
<body>
<div>Dear Diana,</div><br>
<div>Hope you will be fine.</div><br>
{{--        <div>FYI, Can you please create a new user account in <p>TalentLMS</p></div>--}}
<div>FYI, There is something error when trying to auto-create a new user account in <b>Moodle</b></div><br>

Name: <b>{{$cart->userId->en_name}}</b><br>
Online Learning: <b>{{json_encode($cart->course->title)->en??$cart->course->trans_title}}</b><br>
Email: <b>{{$cart->userId->email}}</b><br />
Mobile: <b>{{$cart->userId->mobile}}</b><br />
Username: <b>{{$cart->userId->username_lms}}</b><br />
Password: <b>{{$cart->userId->password_lms}}</b><br /><br />

<div>Best Regards,</div>
<div>{{__('education.app_title')}}</div>
<div>Phone: 920003928</div>
</body>
</html>
