@extends('emails.courses.master')

@section('content')
<style>
    .default_fs p{font-size: 12px;}
</style>

    <table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear <b>{{$cart->userId->en_name}},</b></div><br>
                <div>Welcome onboard!<br />
                    You have registered to attend <b>Online Learning {{$cart->course->en_title??$cart->course->trans_title}}</b>. We would like to welcome you and wish you a great learning experience!<br /><br />

                    This email is intended to provide you with the needed information before the beginning of the course to help you log in our LMS system and deal with the course properly.<br />
{{--                    Kindly check the attachment!<br /><br />--}}
                    Details you need to note:<br />
                    Delivery Method: Online Learning <br />
                    Language: English <br>

                    {{-- @if($cart->course_id!=32 && $cart->course_id!=35 && $cart->course_id!=36 && $cart->course_id!=37) --}}
                    @if($cart->trainingOption->constant_id == 13)
                        Start Date: <b>{{$cart->session->published_from_for_email??null}}</b><br>
                        Duration: {{$cart->session->duration??null}} {{$cart->session->durationType->en_name??null}}<br>
                        Time: <div class="default_fs">{!! $cart->session->session_time??null !!}</div><br>
                        @if(!is_null($cart->session->zoom_link))
                            Join Zoom Meeting: <a href="{!! $cart->session->zoom_link !!}">{!! $cart->session->zoom_link !!}</a><br>
                        @endif
                    @endif

                    <br>
                    <b><a href="{{env('Moodle_URL')}}">The Learning Management System (LMS)</a></b><br />
                    The access below is for our LMS system where you’ll find the course and all supporting materials!<br /><br />

                    Here’s your details:<br />

                    Link: <b><a href="{{env('Moodle_URL')}}">{{env('Moodle_URL')}}</a></b><br />
                    {{-- @if($user_id__found==-1) --}}
                    @if(isset($cart->userId->username_lms))
                        Username:<br /><span style="font-weight: bold;color: #fb4400;">{{$cart->userId->username_lms}}</span><br />
                        Password:<br /><span style="font-weight: bold;color: #fb4400;">{{$cart->userId->password_lms}}</span>
                    {{-- @else
                        You can login with your last credentials. --}}
                    @endif
                    <br /><br />

                    Please ensure that you complete <b>Access Your Knowledge - Pre-Learning</b> before starting the training, you can find it under Trainee Guideline folder.<br />

                    If you face any technical issues and need support, please feel free to contact our Training Department and we’ll get in touch with you in due time!

                    <br><br>
                    We hope you enjoy this learning experience and wish you best of luck!
                    <div>Best Regards,</div>
                    <div>{{__('education.app_title')}}</div>
                    <div>Phone: +966 920003928</div>
                    <div>WhatsApp: +966 55 676 5156</div><br>
                </div>
            </td>
        </tr>

    </table>
@endsection
