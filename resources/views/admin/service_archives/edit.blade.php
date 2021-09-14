@extends(ADMIN.'.general.edit')

@section('edit')

	<?php $folder='service_archives'; ?>
    {{Builder::SetEloquent($eloquent)}}
    {{Builder::SetObject('service_archive')}}
	@include(ADMIN.'.service_archives.form')

@endsection
