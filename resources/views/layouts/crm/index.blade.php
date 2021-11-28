@extends('layouts.crm.master')

@section('content')

	@include('Html.alert')

	@yield('table')
	@yield('js')

@endsection
