@extends(ADMIN.'.general.form')

{!!Builder::SetNameSpace('')!!}
{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetPrefix('crm::admin.')}}

@section('col9')
    {!! Builder::Hidden('organization_id', $eloquent->id??null) !!}
    {!!Builder::Input('en_title', 'organization_en', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('ar_title', 'organization_ar', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('en_name', 'en_name',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('ar_name', 'ar_name',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('email', 'email', null, ['col'=>'col-md-6'])!!}
    {!!Builder::Input('mobile', 'mobile',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('job_title', 'job_title',null,array('col'=>'col-md-6'))!!}
    {!!Builder::Input('address', 'address',null,array('col'=>'col-md-6'))!!}
@endsection

{{-- @section('seo')
	@include(ADMIN.'.SEO.form', ['post'=>$organization??null])
@endsection --}}

{{--  $title = __('admin.table');  --}}
{{--  @section('col3')
    {!!Builder::Input('position_code', 'position_code',null,array('col'=>'col-md-12'))!!}

    {!!Builder::Input('city', 'city',null,array('col'=>'col-md-12'))!!}
@endsection  --}}

@section('image')
	<?php $image_title = __('admin.image'); ?>
	@include(ADMIN.'.Html.image')
@endsection


