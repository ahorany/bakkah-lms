@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetPostType($post_type)}}
	{{Builder::SetTrash($trash)}}
    {{Builder::SetFolder($folder)}}
    {{Builder::SetObject('organization')}}

	@include('crm::organizations.table')

@endsection
