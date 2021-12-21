@extends('layouts.crm.edit')

@section('useHead')
    <title>{{__('education.Edit Course')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('edit')
	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('course')}}
	@include('training.'.$folder.'.form')
@endsection
