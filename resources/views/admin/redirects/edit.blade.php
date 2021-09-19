@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('redirect')}}
	@include('admin.'.$folder.'.form')

@endsection
