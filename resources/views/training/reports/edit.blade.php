@extends(ADMIN.'.general.edit')

@section('edit')
	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('report')}}
	@include('training.'.$folder.'.form')
@endsection
