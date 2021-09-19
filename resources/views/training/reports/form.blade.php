@extends(ADMIN.'.general.form')


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    color: #000;
    padding: 0 10px !important;
}
</style>
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
	{!!Builder::Input('en_title', 'en_title', null, ['col'=>'col-md-6'])!!}
	{!!Builder::Input('ar_title', 'ar_title', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Textarea('en_excerpt', 'en_excerpt', null, [
        'row'=>8,
        'attr'=>'maxlength="1000"',
    ])!!}
    {!!Builder::Textarea('ar_excerpt', 'ar_excerpt', null, [
        'row'=>8,
        'attr'=>'maxlength="1000"',
    ])!!}

    {!!Builder::Tinymce('en_details', 'en_details')!!}
    {!!Builder::Tinymce('ar_details', 'ar_details')!!}

    {{Builder::SetPrefix('admin.')}}
    {!!Builder::Input('slug', 'slug', null)!!}

    {!!Builder::Select2('course_ids[]', 'course_name', $courses, $related_courses, ['model_title'=>'trans_title', 'multiple' => 'multiple'])!!}

    {!!Builder::Select2('article_ids[]', 'article_name', $articles, $related_articles, ['model_title'=>'title', 'multiple' => 'multiple'])!!}
@endsection

@section('col3_block')

    <div class="card card-default">
        <div class="card-header">{{__('admin.options')}}</div>
        <div class="card-body">
            {!!Builder::Input('order', 'order', null)!!}
            {!!Builder::CheckBox('show_in_website')!!}
            <a href="{{route('education.static.reports.single',[$eloquent->slug ,'preview' => 'true'])}}" target="_blank" class="btn btn-primary">Preview</a>
        </div>
    </div>

    @include(ADMIN.'.Html.checkbox_const', ['const_type'=>'course'])

    <div class="card card-default">
        <div class="card-header">{{__('admin.brochure')}}</div>
        <div class="card-body">
            {!!Builder::File('en_pdf', 'en_pdf')!!}
            {!!Builder::File('ar_pdf', 'ar_pdf')!!}
            {!!Builder::PDFForm()!!}
        </div>
    </div>
@endsection

@section('seo')

    @include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])
@endsection

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include(ADMIN.'.Html.image')
@endsection

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function(){
        $('.select2').select2({
            placeholder: "Select Course"
        });
    });
</script>
