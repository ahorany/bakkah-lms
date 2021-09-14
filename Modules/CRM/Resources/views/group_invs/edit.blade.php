@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('group_inv')}}
	@include('crm::group_invs.form')

@endsection
