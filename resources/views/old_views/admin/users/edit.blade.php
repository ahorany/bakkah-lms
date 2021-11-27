@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('user')}}
	@include(ADMIN.'.'.$folder.'.form')

@endsection
