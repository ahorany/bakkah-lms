@extends('layouts.crm.index')

@section('table')

{{--    @include('training.'.$folder.'.search')--}}

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('group')}}

	@include('crm.'.$folder.'.table')

@endsection
