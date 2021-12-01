@extends('layouts.crm.create')

@section('create')
    {{Builder::SetNameSpace('training.')}}
	@include('training.'.$folder.'.form')
@endsection
