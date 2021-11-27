@extends(ADMIN.'.layouts.master')

@section('content')

	@include(ADMIN.'.Html.alert')

	@yield('table')
	@yield('js')

@endsection
