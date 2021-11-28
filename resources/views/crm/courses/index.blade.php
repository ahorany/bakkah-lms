@extends('layouts.crm.index')

@section('table')

    @include('crm.'.$folder.'.search')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('course')}}

	@include('crm.'.$folder.'.table')

@endsection
