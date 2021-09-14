@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
	{{Builder::SetObject('group_invoice')}}
	@include('crm::group_invoices.form')

@endsection
