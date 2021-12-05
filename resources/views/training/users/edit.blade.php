@extends('layouts.crm.edit')

@section('useHead')
    <title>{{__('education.Edit User')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('user')}}
	@include('training.'.$folder.'.form')

@endsection
