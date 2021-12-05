@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Courses')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('table')

    @include('training.'.$folder.'.search')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('course')}}

	@include('training.'.$folder.'.table')

@endsection
