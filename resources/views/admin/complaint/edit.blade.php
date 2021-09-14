@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('complaint')}}
	@include('admin.'.$folder.'.form')

@endsection
