@extends(ADMIN.'.general.index')

@section('table')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPostType($post_type)}}
    {{Builder::SetObject('user')}}

    @include(ADMIN.'.'.$folder.'.search')

	@include(ADMIN.'.'.$folder.'.table')

@endsection
