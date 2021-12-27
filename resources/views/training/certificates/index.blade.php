@extends('layouts.crm.index')

@section('table')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetPrefix('admin.')}}
    {{Builder::SetObject('certificate')}}
    @include('training.'.$folder.'.search')
	@include('training.'.$folder.'.table')

@endsection
