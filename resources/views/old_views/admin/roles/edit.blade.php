@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('role')}}
	@include(ADMIN.'.'.$folder.'.form')

@endsection
