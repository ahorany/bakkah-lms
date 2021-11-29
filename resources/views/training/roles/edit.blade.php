@extends('layouts.crm.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('role')}}
	@include('training.'.$folder.'.form')

@endsection
