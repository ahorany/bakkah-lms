@extends(ADMIN.'.layouts.master')

@section('content')

    <form method="post" action="{{route('admin.details.store')}}">
        @include(ADMIN.'.details.form')
    </form>

@endsection
