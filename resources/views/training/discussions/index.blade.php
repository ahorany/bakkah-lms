@extends('layouts.crm.index')

@section('useHead')
    <title>Discussion | {{ __('home.DC_title') }}</title>
@endsection

@section('table')
	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('discussion')}}
    {{Builder::HasRouteParams(true)}}

	@include('training.'.$folder.'.table')
@endsection
