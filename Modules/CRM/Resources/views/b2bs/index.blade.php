@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetPostType($post_type)}}
    {{Builder::SetTrash($trash)}}
    {{Builder::SetFolder('crm::b2bs')}}
    {{Builder::SetObject('b2b')}}

    @include('crm::b2bs.search')

    @include('crm::b2bs.table')

@endsection
