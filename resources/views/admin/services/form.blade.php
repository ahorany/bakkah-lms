@extends(ADMIN.'.general.form')

{{-- {!!Builder::SetPostType($post_type)!!} --}}
{!!Builder::Hidden('master_id', $master_id??null)!!}

@section('col9')
    {!!Builder::Input('en_title', 'en_title')!!}
    {!!Builder::Input('ar_title', 'ar_title')!!}
    {!!Builder::Tinymce('en_excerpt', 'en_excerpt')!!}
    {!!Builder::Tinymce('ar_excerpt', 'ar_excerpt')!!}
	{{-- {!!Builder::Textarea('en_excerpt', 'en_excerpt', null, ['row'=>3, 'tinymce'=>'tinymce-small',])!!}
	{!!Builder::Textarea('ar_excerpt', 'ar_excerpt', null, ['row'=>3, 'tinymce'=>'tinymce-small',])!!} --}}
	{!!Builder::Number('percentage', 'percentage')!!}
	{!!Builder::Number('order', 'order')!!}
@endsection
