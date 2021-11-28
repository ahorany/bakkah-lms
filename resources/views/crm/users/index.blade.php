@extends('layouts.crm.index')

@section('table')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetPostType($post_type)}}
    {{Builder::SetObject('user')}}



    @include('crm.'.$folder.'.search')

	@include('crm.'.$folder.'.table')

@endsection
