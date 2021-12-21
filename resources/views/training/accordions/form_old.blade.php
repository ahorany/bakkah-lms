@extends(ADMIN.'.general.form')

{{Builder::SetFolder('accordions')}}
{{Builder::SetPrefix('admin.')}}
{{Builder::SetNameSpace('admin.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
	{!!Builder::Input('master_id', 'master_id', request()->master_id??null)!!}
	{!!Builder::Input('en_title', 'en_title', null, ['col'=>'col-md-6'])!!}
	{!!Builder::Input('ar_title', 'ar_title', null, ['col'=>'col-md-6'])!!}
	{!!Builder::Tinymce('en_details', 'en_details')!!}
    {!!Builder::Tinymce('ar_details', 'ar_details')!!}
@endsection

@section('seo')
    @include(ADMIN.'.SEO.form', ['post'=>$post??null])
@endsection
