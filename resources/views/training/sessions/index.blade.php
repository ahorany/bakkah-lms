@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetPostType($post_type)}}
	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('session')}}

    @include('training.'.$folder.'.search')

	@include('training.'.$folder.'.table')

@endsection
