@extends(ADMIN.'.layouts.master')

@section('content')

    {{Builder::SetEloquent($eloquent)}}
    <form method="post" action="{{route('admin.details.update', ['detail'=>$eloquent->id])}}">
        @method('PATCH')
        @include(ADMIN.'.details.form')
    </form>

@stop
