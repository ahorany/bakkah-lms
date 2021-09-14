@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
<link rel="stylesheet" href="{{CustomAsset(ADMIN.'-dist/css/jquery.datetimepicker.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    color: #000;
    padding: 0 10px !important;
}
</style>

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

    {!!Builder::Input('zoom_link', 'zoom_link', null)!!}
    {!!Builder::Input('video_link', 'video_link', null)!!}

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
            <a href="{{route('education.static.webinars.single',[$eloquent->slug ,'preview' => 'true'])}}" target="_blank" class="btn btn-primary">Preview</a>
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header">{{__('admin.options')}}</div>
        <div class="card-body">
            {!!Builder::DateTime('session_start_time', 'session_start_time', null, [ 'col'=>'col-md-12'])!!}
            {!!Builder::DateTime('session_end_time', 'session_end_time', null, [ 'col'=>'col-md-12'])!!}
        </div>
    </div>

    {{-- @include(ADMIN.'.Html.checkbox_const', ['const_type'=>'course']) --}}

@endsection

@section('seo')

    @include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])
@endsection

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include(ADMIN.'.Html.image')
@endsection

<script src="{{CustomAsset(ADMIN.'-dist/js/jquery.datetimepicker.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function(){
       $('[name="session_start_time"]').datetimepicker({
            format:'Y-m-d H:i',
            formatTime:'H:i A',
            dayOfWeekStart : 6,
            // hours12: false,
            // lang:'en',
            // disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
            // startDate: '1986/01/05'
        });
       $('[name="session_end_time"]').datetimepicker({
            format:'Y-m-d H:i',
            formatTime:'H:i A',
            dayOfWeekStart : 6,
            // hours12: false,
            // lang:'en',
            // disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
            // startDate: '1986/01/05'
        });

        $('.select2').select2({
            placeholder: "Select Course"
        });
    });
</script>
