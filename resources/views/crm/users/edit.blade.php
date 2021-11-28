@extends('layouts.crm.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('user')}}
	@include('crm.'.$folder.'.form')

@endsection
