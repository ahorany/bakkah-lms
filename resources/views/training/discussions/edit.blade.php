@extends('layouts.crm.edit')

@section('useHead')
    <title>{{ __('education.Edit Group') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('edit')
	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('user.')}}
	{{Builder::SetObject('discussion')}}
	{{Builder::HasRouteParams(true)}}
	@include('training.'.$folder.'.form')
@endsection
