@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

@section('col9')

    {!!Builder::Input('source_url', 'source_url',null,array('col'=>'col-md-12'))!!}
    {!!Builder::Input('destination_url', 'destination_url',null,array('col'=>'col-md-12'))!!}
    {!!Builder::Select('constant_id', 'redirection_types', $redirection_types, null, [
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


