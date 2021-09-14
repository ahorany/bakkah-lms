@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('contact')}}
	@include('admin.'.$folder.'.form')

@endsection
