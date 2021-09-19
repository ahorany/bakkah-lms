@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetTrash($trash)}}
    {{Builder::SetFolder($folder)}}
    {{Builder::SetPrefix('training.')}}
    {{Builder::SetNameSpace('training.')}}
    {{Builder::SetObject('option')}}

    @include('training.'.$folder.'.table')

@endsection
