@extends('layouts.crm.edit')

@section('edit')
	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('group')}}
	@include('crm.'.$folder.'.form')
@endsection
