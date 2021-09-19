@extends(ADMIN.'.general.form')
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

    {{Builder::SetPrefix('training.')}}
    {!!Builder::Textarea('en_accredited_notes', 'en_accredited_notes', null, [
        'row'=>8,
        'attr'=>'maxlength="1000"',
    ])!!}
    {!!Builder::Textarea('ar_accredited_notes', 'ar_accredited_notes', null, [
        'row'=>8,
        'attr'=>'maxlength="1000"',
    ])!!}

    <div class="card card-default col-md-12">
        <div class="card-header"><b>{{__('admin.cerificate')}}</b></div>
        <div class="card-body">
            {!!Builder::Select('certificate_type_id', 'certificate_type_id', $certificate_types, null, ['col'=>'col-md-6'])!!}
            {!!Builder::Textarea('ar_disclaimer', 'ar_disclaimer', null, [
                'row'=>1,
                'attr'=>'maxlength="1000"',
            ])!!}
            {!!Builder::Textarea('en_disclaimer', 'en_disclaimer', null, [
                'row'=>6,
                'attr'=>'maxlength="1000"',
            ])!!}
        </div>
    </div>

    {{Builder::SetPrefix('admin.')}}
    {!!Builder::Input('slug', 'slug', null)!!}
@endsection

@section('col3_block')
    @if(isset($eloquent->id))
    <div class="card card-default">
        <div class="card-header">{{__('admin.contents')}}</div>
        <div class="card-body">
        <a href="{{route('training.contents',['course_id'=>$eloquent->id])}}" class="btn btn-success add_contents">Add to contents</a>
       
        </div>
    </div>
    @endif
    
    <div class="card card-default">
        <div class="card-header">{{__('admin.options')}}</div>
        <div class="card-body">
            {!!Builder::Input('rating', 'rating', null, ['col'=>'col-md-12'])!!}
            {!!Builder::Input('reviews', 'reviews', null, ['col'=>'col-md-12'])!!}
            {!!Builder::Input('en_short_title', 'en_short_title', null)!!}
            {!!Builder::Input('ar_short_title', 'ar_short_title', null)!!}
            {!!Builder::Input('order', 'order', null)!!}
            {!!Builder::Input('algolia_order', 'algolia_order', null)!!}
            {!!Builder::Input('xero_code', 'xero_code', null)!!}
<<<<<<< HEAD
=======
            {!!Builder::Input('xero_exam_code', 'xero_exam_code', null)!!}
            {!!Builder::Input('xero_exam_code_practitioner', 'xero_exam_code_practitioner', null)!!}
>>>>>>> c89d1697efbbad74dee21aa845b4144b3241f548
            {!! Builder::Input('material_cost', 'material_cost', null, ['attr' => 'digit']) !!}

            {!!Builder::Select('partner_id', 'partners', $partners, null, ['col'=>'col-md-12']) !!}

            <hr>
            <?php $type_id = \App\Constant::where('id', 370)->get(); ?>
            {{-- {!! Builder::Select('type_id', 'type_id', $type_id, null, ['col'=>'col-md-12']) !!}
            {!!Builder::CheckBox('show_in_website')!!}
            <a href="{{route('education.courses',[$eloquent->slug ,'preview' => 'true'])}}" target="_blank" class="btn btn-primary">Preview</a>
            {!!Builder::CheckBox('active')!!} --}}

        </div>
    </div>

    @include(ADMIN.'.Html.checkbox_const', ['const_type'=>'course'])

    @include(ADMIN.'.Html.checkbox_const', ['const_type'=>'language'])

    @include(ADMIN.'.details.call', ['eloquent'=>$eloquent??null])

    @include(ADMIN.'.accordions.call', ['eloquent'=>$eloquent??null])

    <div class="card card-default">
        <div class="card-header">{{__('admin.brochure')}}</div>
        <div class="card-body">
            {!!Builder::File('en_pdf', 'en_pdf')!!}
            {!!Builder::File('ar_pdf', 'ar_pdf')!!}
            {!!Builder::PDFForm()!!}
        </div>
    </div>
    <div class="card card-default">
        <div class="card-header">{{__('admin.intro_video')}}</div>
        <div class="card-body">
            {!!Builder::File('intro_video', 'intro_video')!!}
            {!!Builder::VideoFile('intro_video')!!}
        </div>
    </div>
@endsection

@section('seo')

    <div class="row">
        <div class="col-md-6">
            <div class="card card-default">
                <div class="card-header">{{__('admin.upcoming_courses')}} (EN)</div>
                <div class="card-body">
                    {!!Builder::File('en_image', 'en_image')!!}
                    {!!Builder::UploadFormLang(null, null, ['post_type'=>'en_image'])!!}
                    <?php
                    $en_upload_title = null;
                    $en_upload_excerpt = null;
                    if(!is_null(Builder::$eloquent)){
                        $en_upload_title = Builder::$eloquent->uploads()->where('post_type', 'en_image')->first()->title??null;
                        $en_upload_excerpt = Builder::$eloquent->uploads()->where('post_type', 'en_image')->first()->excerpt??null;
                    }
                    ?>
                    {!!Builder::Input('en_upload_title', 'title', $en_upload_title)!!}
                    {!!Builder::Excerpt('en_upload_excerpt', 'excerpt', $en_upload_excerpt)!!}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-default">
                <div class="card-header">{{__('admin.upcoming_courses')}} (AR)</div>
                <div class="card-body">
                    {!!Builder::File('ar_image', 'ar_image')!!}
                    {!!Builder::UploadFormLang(null, null, ['post_type'=>'ar_image'])!!}
                    <?php
                    $ar_upload_title = null;
                    $ar_upload_excerpt = null;
                    if(!is_null(Builder::$eloquent)){
                        $ar_upload_title = Builder::$eloquent->uploads()->where('post_type', 'ar_image')->first()->title??null;
                        $ar_upload_excerpt = Builder::$eloquent->uploads()->where('post_type', 'ar_image')->first()->excerpt??null;
                    }
                    ?>
                    {!!Builder::Input('ar_upload_title', 'title', $ar_upload_title)!!}
                    {!!Builder::Excerpt('ar_upload_excerpt', 'excerpt', $ar_upload_excerpt)!!}
                </div>
            </div>
        </div>
    </div>

    @include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])
@endsection

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include(ADMIN.'.Html.image')
@endsection

