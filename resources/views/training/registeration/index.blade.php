@extends(ADMIN.'.general.index')

@section('table')
	@include('training.'.$folder.'.search')
  
	@include('training.'.$folder.'.table')
@endsection
