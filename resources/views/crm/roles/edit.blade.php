@extends('layouts.crm.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('role')}}
	@include('crm.'.$folder.'.form')

@endsection
