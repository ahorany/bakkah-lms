@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('career')}}
	@include('admin.'.$folder.'.form')

@endsection
