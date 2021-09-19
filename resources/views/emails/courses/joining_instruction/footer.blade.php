<table width="600" align="center" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
        <td style="font-family: Tahoma,sans-serif;padding-top:30px;color:#585858;font-size:14px;line-height: 1.5;">
            <h4>PRE/POST - COURSE READINGS</h4>
            <p>You can download all the training materials as well as other supporting material. No Pre-reading is required before commencing of the course.</p>
            <p>You can have exam simulations online through our e-Training Portal.</p>

            <div>
                <span style="display:inline-block;width:100px;padding-bottom:5px">Course Name: </span>
                <?php
                // $href='https://learn.bakkah.net.sa/login/index.php';
                $href=env('Moodle_URL');
                if($payment->cart->trainingOption->constant_id==11){
                    // $href='https://lms.bakkah.net.sa/';
                    $href=env('TalentLMS_URL');
                }
                ?>
                <a href="{{$href}}" target="_blank">
                    {{$payment->cart->course->en_title}}
                </a> <br>
                <span style="display:inline-block;width:100px;padding-bottom:5px">Username: </span> <span style="color:#c00000">{{$payment->cart->userId->username_lms}}</span> <br>
                <span style="display:inline-block;width:100px;padding-bottom:5px">Password: </span> <span style="color:#c00000">{{$payment->cart->userId->password_lms}}</span> <br>
            </div>
            <p>We urge to apply for the certificate and here is important information you need to know: </p>

            <br>
            <br>

            @if(isset($payment->cart->course->detail()->where('constant_id', 309)->first()->trans_details))
                <h4>CERTIFICATION ELIGIBILITY </h4>
                    {!! $payment->cart->course->detail()->where('constant_id', 309)->first()->trans_details !!}
                {{--<ul>
                    <li>To be eligible for the aPHR you must have a high school diploma or global equivalent.</li>
                    <li>No HR experience is required since this is a knowledge-based credential.</li>
                </ul>--}}
                <br>
            @endif

            @if(isset($payment->cart->course->detail()->where('constant_id', 310)->first()->trans_details))
                <h4>MORE INFO </h4>
                    {!! $payment->cart->course->detail()->where('constant_id', 310)->first()->trans_details !!}
                {{--<ul>
                    <li><a href="https://www.hrci.org/our-programs/our-certifications/aphri">https://www.hrci.org/our-programs/our-certifications/aphri</a></li>
                    <li><a href="https://bakkah.net.sa/sessions/aphri/">https://bakkah.net.sa/sessions/aphri/</a></li>
                </ul>--}}
                <br>
                <br>
                <br>
                <br>
            @endif

            <p>If you have any queries or concerns, please feel free to contact our Training Delivery Team at training@bakkah.net.sa and 00966 55 676 5156.</p>
            <br>
            <br>
            <p>Best Regards, <br>Training Delivery Team </p>
        </td>
    </tr>
    </tbody>
</table>
