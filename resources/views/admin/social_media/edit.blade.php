@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('social_media')}}
	@include('admin.'.$folder.'.form')

@endsection
