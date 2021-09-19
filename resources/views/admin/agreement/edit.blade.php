@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('agreement')}}
	@include('admin.'.$folder.'.form')

@endsection
