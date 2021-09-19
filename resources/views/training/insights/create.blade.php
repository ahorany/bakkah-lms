@extends(ADMIN.'.general.create')

@section('create')
    {{Builder::SetNameSpace('training.')}}
	@include('training.'.$folder.'.form')
@endsection
