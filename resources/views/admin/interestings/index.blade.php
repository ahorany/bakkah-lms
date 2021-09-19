@extends(ADMIN.'.general.index')

@section('table')

	{{Builder::SetPostType($post_type)}}
	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetObject('interesting')}}

	@include(ADMIN.'.'.$folder.'.table')

@endsection
