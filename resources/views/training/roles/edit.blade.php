@extends('layouts.crm.edit')

@section('useHead')
    <title>{{__('education.Edit Role')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('role')}}
	@include('training.'.$folder.'.form')

@endsection
