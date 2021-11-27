@extends(ADMIN.'.general.form')
<link rel="stylesheet" href="{{CustomAsset(ADMIN.'-dist/css/jquery.datetimepicker.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
    color: #000;
    padding: 0 10px !important;
}
</style>
{!!Builder::SetPostType($post_type)!!}
{!!Builder::Hidden('origin_id', $origin_id??null)!!}
{!!Builder::Hidden('locale', $locale??null)!!}

@section('col9')
	{!!Builder::Input('title', 'title')!!}
	{!!Builder::Tinymce('details', 'details')!!}
	{!!Builder::Textarea('excerpt', 'excerpt', null, ['row'=>3])!!}
	{!!Builder::Input('slug', 'slug')!!}
	{!!Builder::Input('url', 'url')!!}
    @if ($post_type == 'knowledge-center')
	    {!!Builder::Select2('course_ids[]', 'course_name', $courses, $related_courses, ['model_title'=>'trans_title', 'multiple' => 'multiple'])!!}
    @endif
@endsection

@section('seo')
	@include(ADMIN.'.SEO.form', ['post'=>$post??null])
@endsection

@section('col3')
	<?php $title = __('admin.table'); ?>
    {!!Builder::DateTime('post_date', 'post_date')!!}

    {!!Builder::CheckBox('show_in_website')!!}
    @if (isset($eloquent))
    <a href="{{route('education.static.knowledge-center.single',[$eloquent->slug??null ,'preview' => 'true'])}}" target="_blank" class="btn btn-primary">Preview</a>
    @endif


    @if ($post_type == 'education-slider' || $post_type == 'modal-campaign' || $post_type == 'navbar-campaign')

        {!!Builder::DateTime('date_to', 'date_to')!!}

        {!!Builder::Select('country_id', 'country_id', $countries, null, [
            'col'=>'col-md-12', 'model_title'=>'trans_name',
        ])!!}

        {!!Builder::Select('coin_id', 'coin_id', $coins, null, [
            'col'=>'col-md-12', 'model_title'=>'trans_name',
        ])!!}
    @endif
@endsection

@section('col3_block')
	<!-- knowledge_center -->
    <?php
//    if($post_type=='consulting-insights'){
//        $post_type = 'consulting';
//    }
    ?>
    @include(ADMIN.'.Html.checkbox_const', ['const_type'=>$post_type])

    @includeWhen(($post_type=='consulting-service'), ADMIN.'.services.call_in_post')

@endsection

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include(ADMIN.'.Html.image')
@endsection

<script src="{{CustomAsset(ADMIN.'-dist/js/jquery.datetimepicker.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function(){
        $('[data-date="datetime"]').datetimepicker({
            format:'Y-m-d H:i',
            dayOfWeekStart : 6,
        });

        $('.select2').select2({
            placeholder: "Select Course"
        });
    });
</script>
