@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('constant')}}
	@include(ADMIN.'.'.$folder.'.form')

@endsection
