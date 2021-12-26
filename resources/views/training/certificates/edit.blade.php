@extends('layouts.crm.edit')
@section('edit')

	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('certificate')}}
	@include('training.'.$folder.'.form')

@endsection

