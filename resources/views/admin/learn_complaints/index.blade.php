@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetPostType($post_type)}}
	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetObject('learn_complaints')}}

    @include('admin.'.$folder.'.search')

	@include('admin.'.$folder.'.table')

@endsection
