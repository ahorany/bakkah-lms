@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetPostType($post_type)}}
	{{Builder::SetTrash($trash)}}
	{{Builder::SetFolder($folder)}}
	{{Builder::SetObject('contact')}}

    <a class="d-none" href="{{route('admin.contacts.export')}}">Export</a>

	@include('admin.'.$folder.'.table')

@endsection
