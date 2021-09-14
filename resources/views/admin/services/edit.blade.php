@extends(ADMIN.'.general.edit')

@section('edit')

	<?php $folder='services'; ?>
    {{Builder::SetEloquent($eloquent)}}
    {{Builder::SetObject('service')}}
	@include(ADMIN.'.services.form')

@endsection
