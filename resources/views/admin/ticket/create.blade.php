@extends(ADMIN.'.general.create')

@section('create')
	@include('admin.'.$folder.'.form',['action' => 'create'])
@endsection
