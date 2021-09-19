@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

@section('col9')
	{!!Builder::Input('en_name', 'en_name', null, ['col'=>'col-md-6'])!!}
	{!!Builder::Input('ar_name', 'ar_name', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Excerpt('en_excerpt', 'en_excerpt')!!}
    {!!Builder::Excerpt('ar_excerpt', 'ar_excerpt')!!}
    {!!Builder::Tinymce('en_details', 'en_details')!!}
    {!!Builder::Tinymce('ar_details', 'ar_details')!!}
    {!!Builder::Input('slug', 'slug')!!}
@endsection

@section('seo')
	@include(ADMIN.'.SEO.form', ['post'=>$post??null])
@endsection

@section('col3')
    <?php $title = __('admin.table'); ?>
    {!!Builder::Date('post_date', 'post_date')!!}
    {!!Builder::Input('rep', 'rep')!!}
    {!!Builder::Number('order', 'order')!!}
    {!!Builder::Input('xero_code', 'xero_code', null, ['col'=>'col-md-12'])!!}
    {!!Builder::CheckBox('show_in_home')!!}
@endsection

@section('col3_block')
    <div class="card card-default">
        <div class="card-header">{{__('admin.Position')}}</div>
        <div class="card-body">
            {!! Builder::CheckBox('in_education') !!}
            {!! Builder::CheckBox('in_consulting') !!}
        </div>
    </div>
@endsection

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include(ADMIN.'.Html.image')
@endsection
