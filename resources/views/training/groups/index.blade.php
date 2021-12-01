@extends('layouts.crm.index')

@section('table')

{{--    @include('training.'.$folder.'.search')--}}

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('group')}}

	@include('training.'.$folder.'.table')

	{{-- @include('crm.'.$folder.'.search') --}}

@endsection
