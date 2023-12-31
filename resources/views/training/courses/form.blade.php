@extends('layouts.crm.form')
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
    @isset($eloquent->id)
      <div class="col-12 mb-3">Courese ID: <span class="bg-dark text-white px-2 py-1" style="border-radius: 5px">{{$eloquent->id}}</span></div>
    @endisset

    <div class="col-12">
        <div class="row mx-0 inputs">

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

            {!!Builder::Select('category_id', 'category_id', $categories, null, ['col'=>'col-md-6', 'model_title'=>'trans_title',])!!}
            {!!Builder::Input('ref_id', 'ref_id', null, ['col'=>'col-md-6'])!!}

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
                        <?php if(is_dynamic_certificate() == 1) { ?>
                                {!!Builder::Select('certificate_id', 'certificate_id', $certificate_ids, null, ['col'=>'col-md-6', 'model_title'=>'trans_title',])!!}
                        <?php }else{ ?>
                                {!!Builder::Select('certificate_type_id', 'certificate_type_id', $certificate_types, null, ['col'=>'col-md-6', 'model_title'=>'trans_name',])!!}
                        <?php } ?>

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
        </div>
    </div>
@endsection



@section('col3_block')
  <div class="inputs">
    @if(isset($eloquent->id))
        <div class="card card-default contents">
            <div class="card-header">{{__('admin.contents')}}</div>

            <div class="card-body">
                @include('training.courses.contents.header',['course_id' => $eloquent , 'courses_home' =>true])
            </div>
        </div>
    @endif


    <div class="card card-default options">
        <div class="card-header">{{__('admin.options')}}</div>

        <div class="card-body">
            {!!Builder::Input('complete_progress', 'complete progress', isset($eloquent) ? null : COMPLETED_PROGRESS, ['col'=>'col-md-12'])!!}

            {!!Builder::Input('code', 'code', null, ['col'=>'col-md-12'])!!}

            {!! Builder::Select('training_option_id', 'training_option_id', $delivery_methods, null, ['col'=>'col-md-12']) !!}

            {!!Builder::Input('rating', 'rating', null, ['col'=>'col-md-12'])!!}
            {!!Builder::Input('reviews', 'reviews', null, ['col'=>'col-md-12'])!!}
            {!!Builder::Input('en_short_title', 'en_short_title', null)!!}
            {!!Builder::Input('ar_short_title', 'ar_short_title', null)!!}
            {!!Builder::Input('order', 'order', null)!!}
            {!!Builder::Input('PDUs', 'PDUs', null)!!}
        </div>
    </div>

    <div class="card card-default">
        <div class="card-header">{{__('admin.intro_video')}}</div>

        <div class="card-body">
            {!!Builder::File('intro_video', 'intro_video')!!}
            {!!Builder::VideoFile('intro_video')!!}
        </div>
    </div>
</div>
@endsection



@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include('Html.image')
@endsection

