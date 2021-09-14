@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('partner')}}
	@include('admin.'.$folder.'.form')

@endsection
