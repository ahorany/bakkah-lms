@extends(ADMIN.'.general.form')
<link rel="stylesheet" href="{{CustomAsset(ADMIN.'-dist/css/jquery.datetimepicker.css')}}">

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



    {!!Builder::CheckBox('active', 'active', null, ['col'=>'col-md-6'])!!}

    {!!Builder::DateTime('expire_date', 'expire_date')!!}

@endsection


@section('seo')
    @include(ADMIN.'.SEO.form', ['post'=>$eloquent??null])
@endsection

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include(ADMIN.'.Html.image')
@endsection

<script src="{{CustomAsset(ADMIN.'-dist/js/jquery.datetimepicker.js')}}"></script>


<script>
    $(function(){
        $('[data-date="datetime"]').datetimepicker({
            format:'Y-m-d H:i',
            dayOfWeekStart : 6,
        });
    });
</script>
