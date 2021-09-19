@extends(ADMIN.'.general.form')


{{Builder::SetNameSpace('training.')}}
{{Builder::SetFolder($folder)}}

@section('col9')
    {!!Builder::Select('user_id', 'user_id', $users, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}

    {!!Builder::Select('session_id', 'session_id', $sessions, null, [
        'col'=>'col-md-6', 'model_title'=>'session_details',
    ])!!}


    {!!Builder::Select('status', 'status', $status, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}


@endsection

