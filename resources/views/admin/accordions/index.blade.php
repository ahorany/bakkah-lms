@extends(ADMIN.'.general.index')

@section('table')

	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetPrefix('admin.')}}
    {{Builder::SetNameSpace('admin.')}}
	{{Builder::SetObject('accordion')}}

	@include('admin.'.$folder.'.table')

@endsection