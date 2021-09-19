@extends(ADMIN.'.general.edit')

@section('edit')
	{{Builder::SetEloquent($eloquent)}}
    {{Builder::SetNameSpace('training.')}}
	{{Builder::SetObject('gross_margin')}}
    {{-- {{Builder::SetFolder('gross-margin')}} --}}
	@include('training.'.$folder.'.create')
@endsection
