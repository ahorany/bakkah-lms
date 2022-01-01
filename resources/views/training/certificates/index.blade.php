@extends('layouts.crm.index')

@section('useHead')
    <title>{{__('education.Courses')}} | {{ __('admin.certificates') }}</title>
@endsection

@section('table')
	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetPrefix('admin.')}}
    {{Builder::SetObject('certificate')}}
    @include('training.'.$folder.'.search')
	@include('training.'.$folder.'.table')
@endsection
