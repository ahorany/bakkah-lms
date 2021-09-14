@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('b2b')}}
	@include('crm::b2bs.form')

@endsection
