@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

@section('col9')

    {!!Builder::Input('name', 'name',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('email', 'email',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('mobile', 'mobile',null,array('col'=>'col-md-6'))!!}

    {!!Builder::Select('request_type', 'request_type', $request_type, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}

    {!!Builder::Textarea('message', 'message', null, [
         'row'=>5,
         'tinymce'=>'tinymce-small',
     ])!!}
@endsection



