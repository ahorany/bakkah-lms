@extends(ADMIN.'.general.edit')

@section('edit')

	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('training_option')}}
	@include('training.'.$folder.'.form')

@endsection
