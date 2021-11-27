@extends(ADMIN.'.general.index')

@section('table')

    @include('training.'.$folder.'.search')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('course')}}

	@include('training.'.$folder.'.table')

@endsection
