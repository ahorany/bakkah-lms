@extends(ADMIN.'.general.edit')

@section('edit')

	<?php $folder='posts'; ?>
	{{Builder::SetEloquent($eloquent)}}
	@include(ADMIN.'.posts.form')

@endsection
