@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetPostType($post_type)}}
	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetObject('performance')}}

    @include('admin.'.$folder.'.search')

	@include('admin.'.$folder.'.table')

@endsection
