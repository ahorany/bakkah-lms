@switch ($cart->retarget_email_id)
    @case(361)
        We have noticed that you are interested in <b>{{$training_name}}</b>. We want to inform you that the discount will be valid for a limited time. We are looking forward to having you with us by completing the registration process via this link:<br><br>
    @break;

    @case(362)
        Don't miss the chance to take <b>{{$training_name}}</b>. Enroll now via the below link:<br><br>
    @break;

    @case(363)
        The offer ends soon!<br>
        Seize the opportunity and complete the registration process via this link:<br><br>
    @break;

    @case(364)
        We have noticed that you are interested in <b>{{$training_name}}</b>. We are looking forward to having you with us by completeing the registration process via the link:<br><br>
    @break;

    @case(365)
        We have noticed that you are interested in <b>{{$training_name}}</b>. We are looking forward to having you with us by completeing the registration process via the link:<br><br>
    @break;

    @default
        Seize the opportunity and complete the registration process via this link:<br><br>
@endswitch
