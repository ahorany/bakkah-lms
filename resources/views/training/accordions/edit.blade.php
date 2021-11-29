@extends(ADMIN.'.layouts.master')

@section('content')

    {{Builder::SetEloquent($eloquent)}}
    <form method="post" action="{{route('admin.accordions.update', ['accordion'=>$eloquent->id])}}">
        @method('PATCH')
        @include(ADMIN.'.accordions.form')
    </form>

@stop
