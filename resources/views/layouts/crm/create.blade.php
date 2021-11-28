@extends('layouts.crm.master')

@section('content')

	<form method="post" action="{{route(Builder::$namespace.$folder.'.store', ['post_type'=>$post_type??null])}}" enctype="multipart/form-data">
		@yield('create')
	</form>

@endsection
