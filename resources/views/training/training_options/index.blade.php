@extends(ADMIN.'.general.index')

@section('table')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetPrefix('admin.')}}
    {{Builder::SetObject('training_option')}}
    @include('training.'.$folder.'.search')
	@include('training.'.$folder.'.table')

@endsection
