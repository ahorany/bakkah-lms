@extends('layouts.crm.form')
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
    @isset($eloquent->id)
      <div class="col-12 mb-3">Courese ID: <span class="bg-dark text-white px-2 py-1" style="border-radius: 5px">{{$eloquent->id}}</span></div>
    @endisset
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

    {!!Builder::Select('group_id', 'group', $groups, null, ['col'=>'col-md-12','model_title' => 'name']) !!}

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
            <a href="{{route('training.contents',['course_id'=>$eloquent->id])}}" class="btn btn-outline-success add_contents mb-2 ">Contents</a>
            <a href="{{route('training.units',['course_id'=>$eloquent->id])}}" class="btn btn-outline-success add_contents mb-2">Units</a>
            <a href="{{route('training.course_users',['course_id'=>$eloquent->id])}}" class="btn btn-outline-success add_contents mb-2">Users</a>
        </div>
    </div>
    @endif

    <div class="card card-default">
        <div class="card-header">{{__('admin.options')}}</div>
        <div class="card-body">
            {!! Builder::Select('training_option_id', 'training_option_id', $delivery_methods, null, ['col'=>'col-md-12']) !!}

            {!!Builder::Input('rating', 'rating', null, ['col'=>'col-md-12'])!!}
            {!!Builder::Input('reviews', 'reviews', null, ['col'=>'col-md-12'])!!}
            {!!Builder::Input('en_short_title', 'en_short_title', null)!!}
            {!!Builder::Input('ar_short_title', 'ar_short_title', null)!!}
            {!!Builder::Input('order', 'order', null)!!}
{{--            {!!Builder::Input('algolia_order', 'algolia_order', null)!!}--}}
{{--            {!!Builder::Input('xero_code', 'xero_code', null)!!}--}}
{{--            {!!Builder::Input('xero_exam_code', 'xero_exam_code', null)!!}--}}
{{--            {!!Builder::Input('xero_exam_code_practitioner', 'xero_exam_code_practitioner', null)!!}--}}
{{--            {!! Builder::Input('material_cost', 'material_cost', null, ['attr' => 'digit']) !!}--}}

            {!!Builder::Select('partner_id', 'partners', $partners, null, ['col'=>'col-md-12']) !!}



            <hr>
            <?php $type_id = \App\Constant::where('id', 370)->get(); ?>
            {{-- {!! Builder::Select('type_id', 'type_id', $type_id, null, ['col'=>'col-md-12']) !!}
            {!!Builder::CheckBox('show_in_website')!!}
            <a href="{{route('education.courses',[$eloquent->slug ,'preview' => 'true'])}}" target="_blank" class="btn btn-primary">Preview</a>
            {!!Builder::CheckBox('active')!!} --}}

        </div>
    </div>

    @include(ADMIN.'Html.checkbox_const', ['const_type'=>'course'])

    @include(ADMIN.'Html.checkbox_const', ['const_type'=>'language'])

    @include(ADMIN.'details.call', ['eloquent'=>$eloquent??null])

    @include(ADMIN.'accordions.call', ['eloquent'=>$eloquent??null])

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


    @include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])
@endsection

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include(ADMIN.'.Html.image')
@endsection

