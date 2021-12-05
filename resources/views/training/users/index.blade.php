@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Users')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetPostType($post_type)}}
    {{Builder::SetObject('user')}}


    @include('training.'.$folder.'.search')

	@include('training.'.$folder.'.table')

@endsection
