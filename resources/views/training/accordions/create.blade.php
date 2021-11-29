@extends(ADMIN.'.layouts.master')

@section('content')

    <form method="post" action="{{route('admin.accordions.store')}}">
        @include(ADMIN.'.accordions.form')
    </form>

@endsection
