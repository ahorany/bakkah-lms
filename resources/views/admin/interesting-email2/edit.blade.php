@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('interestings-email2')}}
	@include('admin.'.$folder.'.form')

@endsection
