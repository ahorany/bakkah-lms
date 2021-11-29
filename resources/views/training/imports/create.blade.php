@extends('layouts.crm.create')

@section('create')
    {{Builder::SetNameSpace('training.')}}
	@include('crm.'.$folder.'.form')
@endsection
