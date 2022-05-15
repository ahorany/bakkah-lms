@extends('layouts.crm.index')

{{-- @section('title', __('admin.roles') . ' | ' . env('APP_NAME')) --}}

@section('useHead')
    <title>{{__('education.Roles')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')
    {{Builder::SetTrash($trash)}}
    {{Builder::SetFolder($folder)}}
    {{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
    {{Builder::SetPostType($post_type)}}
    {{Builder::SetObject('role')}}

    @include('training.'.$folder.'.search')

	@include('training.'.$folder.'.table')

@endsection
