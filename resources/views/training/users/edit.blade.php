@extends('layouts.crm.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('user')}}
	@include('training.'.$folder.'.form')

@endsection
