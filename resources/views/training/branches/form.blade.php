@extends('layouts.crm.form')
<link rel="stylesheet" href="{{CustomAsset('assets/css/jquery.datetimepicker.css')}}">

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
    {!!Builder::Input('name', 'name', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('title', 'title', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Textarea('description', 'description', null, [
        'row'=>8,
        'attr'=>'maxlength="1000"',
    ])!!}
    {!!Builder::DateTime('expire_date', 'expire_date')!!}
    {!!Builder::CheckBox1('active', 'active', null, ['col'=>'col-md-6'])!!}

@endsection

@section('col3_block')
    <div class="card pt-3 mb-2">
        {!!Builder::Input('main_color', 'main_color', null, ['col'=>'col-md-12' , 'type'=> 'color'])!!}
        {!!Builder::Select('timezone', 'timezone', $timezones, null, ['col'=>'col-md-12' , 'model_title'=>'name',])!!}
    </div>
@endsection

{{--@section('seo')--}}
{{--    @include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])--}}
{{--@endsection--}}

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include('Html.image')
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{CustomAsset('assets/js/jquery.datetimepicker.js')}}"></script>


    <script>
        $(function(){
            $('[data-date="datetime"]').datetimepicker({
                format:'Y-m-d H:i',
                dayOfWeekStart : 6,
            });
        });
    </script>
@endsection
