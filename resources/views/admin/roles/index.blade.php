@extends(ADMIN.'.general.index')

@section('title', __('admin.roles') . ' | ' . env('APP_NAME'))

@section('table')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetObject('role')}}

    @include(ADMIN.'.'.$folder.'.search')

	@include(ADMIN.'.'.$folder.'.table')

@endsection
