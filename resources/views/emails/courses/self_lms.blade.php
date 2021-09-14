@extends('emails.courses.master')

@section('content')

    <table align="center" style="width: 600px; padding: 5px 0;padding-bottom:0;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="background: #fff; font-weight:300; font-size: 12px; color: #707070; font-family:sans-serif; padding: 0px 35px; line-height: 25px;border: 2px solid #d6d6d6;">
                <br>
                <div>Dear <b>{{$cart->userId->trans_name}},</b></div><br>
                <div>Welcome onboard!<br />
                    You have registered to attend <b>Self-Study {{json_encode($cart->course->title)->en??$cart->course->trans_title}}</b>. We would like to welcome you and wish you a great learning experience!<br /><br />

                    This email is intended to provide you with the needed information before the beginning of the course to help you log in our LMS system and deal with the course properly.<br />
{{--                    Kindly check the attachment!<br /><br />--}}
                    Details you need to note:<br />
                    Delivery Method: Self-Study <br />
                    Language: English <br /><br />

                    <b><a href="{{env('TalentLMS_URL')}}">The Learning Management System (LMS)</a></b><br />
                    The access below is for our LMS system where you’ll find the course and all supporting materials!<br /><br />

                    Here’s your details:<br />

                    Link: <b><a href="{{env('TalentLMS_URL')}}">{{env('TalentLMS_URL')}}</a></b><br />
                    @if(isset($cart->userId->username_lms))
                        Username:<br /><span style="font-weight: bold;color: #fb4400;">{{$cart->userId->username_lms}}</span><br />
                        Password:<br /><span style="font-weight: bold;color: #fb4400;">{{$cart->userId->password_lms}}</span>
                    {{-- @else
                        You can login with your last credentials. --}}
                    @endif
                    <br /><br />

                    @if($cart->exam_simulation_price > 0)
                        Exam Simulator account Link: <b><a href="{{env('Moodle_URL')}}">{{env('Moodle_URL')}}</a></b><br />
                        You can login with the above credentials.
                    @endif

                    To learn more about Bakkah's Learning Management System
                    <div style="text-align: center;margin-top: 15px">
                        <a style="text-decoration: none;background-color: #fb4400;color: #fff;padding: 5px 15px;font-size: 16px;font-weight: 400;border-radius: 5px;" href="https://youtu.be/16fh4fqs_f4">Click Here</a>
                    </div><br />

                    If you face any technical issues and need support, please feel free to contact our Training Department and we’ll get in touch with you in due time!

                    <br><br>
                    We hope you enjoy this learning experience and wish you best of luck!
                    <div>Best Regards,</div>
                    <div>{{__('education.app_title')}}</div>
                    <div>Phone: +966 920003928</div>
                    <div>Mobile: +966 55 676 5156</div><br />
                </div>
            </td>
        </tr>

    </table>
@endsection
