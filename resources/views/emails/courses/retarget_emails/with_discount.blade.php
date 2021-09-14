@switch ($cart->retarget_email_id)
    @case(361)
        We have noticed that you are interested in <b>{{$training_name}}</b>. We are looking forward to having you with us by completeing the registration process via this link:<br><br>
    @break;

    @case(362)
        Congratulations! You got a {{$value}}% discount on <b>{{$training_name}}</b>. Complete the registration process via the below link:<br><br>
    @break;

    @case(363)
        Your last chance to get a {{$value}}% discount!<br>
        Seize the opportunity and complete the registration process via this link:<br><br>
        Discount is valid for 24 hours only!<br><br>
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
