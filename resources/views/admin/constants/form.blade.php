@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetPublishName('save')}}

@section('col9')
	{!!Builder::Input('en_name', 'en_name', null, ['col'=>'col-md-6'])!!}
	{!!Builder::Input('ar_name', 'ar_name', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Textarea('en_excerpt', 'en_excerpt', null, [
        'row'=>8,
        'attr'=>'maxlength="1000"',
    ])!!}
    {!!Builder::Textarea('ar_excerpt', 'ar_excerpt', null, [
        'row'=>8,
        'attr'=>'maxlength="1000"',
    ])!!}
@endsection

@section('col3_block')
    <div class="card card-default">
        <div class="card-body">
            {!!Builder::Input('xero_code', 'xero_code', null, ['col'=>'col-md-12'])!!}
        </div>
    </div>
@endsection

@section('seo')
    @include(ADMIN.'.SEO.form', ['post'=>$post??null])
@endsection
