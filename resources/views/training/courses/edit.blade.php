@extends('layouts.crm.edit')

@section('edit')
	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('course')}}
	@include('training.'.$folder.'.form')
@endsection
