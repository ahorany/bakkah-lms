@extends(ADMIN.'.layouts.master')

@section('content')

  <form method="post" action="{{route(Builder::$namespace.$folder.'.update', [Builder::$object=>Builder::$eloquent->id])}}" enctype="multipart/form-data">
  	  @method('PATCH')
	  @yield('edit')
  </form>

@stop
