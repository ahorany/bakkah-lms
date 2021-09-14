@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

@section('col9')
    {!!Builder::Input('en_title', 'en_title',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('ar_title', 'ar_title',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('description', 'description',null,array('col'=>'col-md-12'))!!}
    {!!Builder::Select('social_media', 'social_media', $social_media_type, null, [
        'col'=>'col-md-12', 'model_title'=>'trans_name',
    ])!!}

@endsection

@section('seo')
	{{-- @include(ADMIN.'.SEO.form', ['post'=>$post??null]) --}}
@endsection

@section('col3')
	{{-- <?php //$title = __('admin.table'); ?> --}}
    {{-- {!!Builder::Input('position_code', 'position_code',null,array('col'=>'col-md-12'))!!} --}}
{{--
    {!!Builder::Select('career_type_id', 'career_type_id', $career_types, null, [
        'col'=>'col-md-12', 'model_title'=>'trans_name',
    ])!!}

    {!!Builder::Select('country_id', 'country', $countries, null, [
        'col'=>'col-md-12', 'model_title'=>'trans_name',
    ])!!} --}}
    {{-- {!!Builder::Input('city', 'city',null,array('col'=>'col-md-12'))!!} --}}
@endsection


