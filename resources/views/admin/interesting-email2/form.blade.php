@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

@section('col9')

    {!!Builder::Input('en_title', 'en_title',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('ar_title', 'ar_title',null,array('col'=>'col-md-6'))!!}



    {!!Builder::Textarea('details', 'details', null, [
         'row'=>10,
         'tinymce'=>'tinymce-small',
     ])!!}
@endsection



