@extends('layouts.crm.edit')

@section('useHead')
    <title>{{ __('education.Edit Group') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('edit')
	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('group')}}
	@include('training.'.$folder.'.form')
@endsection
