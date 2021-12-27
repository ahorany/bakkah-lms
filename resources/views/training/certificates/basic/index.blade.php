@extends(ADMIN.'.general.index')

@section('table')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetPrefix('admin.')}}
    {{Builder::SetObject('project')}}
    @include('training.'.$folder.'.search')
	@include('training.'.$folder.'.table')

@endsection
