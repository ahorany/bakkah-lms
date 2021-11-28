@extends('layouts.crm.index')

@section('title', __('admin.roles') . ' | ' . env('APP_NAME'))

@section('table')
    {{Builder::SetTrash($trash)}}
    {{Builder::SetFolder($folder)}}
    {{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
    {{Builder::SetPostType($post_type)}}
    {{Builder::SetObject('role')}}

    @include('crm.'.$folder.'.search')

	@include('crm.'.$folder.'.table')

@endsection
