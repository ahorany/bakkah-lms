@extends('layouts.crm.index')

{{-- @section('title', __('admin.roles') . ' | ' . env('APP_NAME')) --}}

@section('useHead')
    <title>{{__('education.Sessions')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')

{{--    @include('training.'.$folder.'.search')--}}
	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('session')}}

	@include('training.'.$folder.'.table')

@endsection
