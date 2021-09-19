@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('testimonial')}}
	@include('admin.'.$folder.'.form')

@endsection
