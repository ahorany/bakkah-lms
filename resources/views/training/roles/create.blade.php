@extends('layouts.crm.create')

@section('useHead')
    <title>{{__('education.Create Role')}}</title>
@endsection

@section('create')
	@include('training.'.$folder.'.form')
@endsection
