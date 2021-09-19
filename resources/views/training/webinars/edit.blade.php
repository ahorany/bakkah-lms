@extends(ADMIN.'.general.edit')

@section('edit')
	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('webinar')}}
	@include('training.'.$folder.'.form')
@endsection
