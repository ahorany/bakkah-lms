@extends(ADMIN.'.general.form')

{{Builder::SetNameSpace('training.')}}
{{Builder::SetFolder($folder)}}
@section('col9')
    {!!Builder::Input('en_title','en_title', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('ar_title','ar_title', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Excerpt('en_excerpt','en_excerpt', null, ['col'=>'col-md-12'])!!}
    {!!Builder::Excerpt('ar_excerpt','ar_excerpt', null, ['col'=>'col-md-12'])!!}
@endsection
