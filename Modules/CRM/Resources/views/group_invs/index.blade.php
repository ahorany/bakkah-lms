@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetPostType($post_type)}}
    {{Builder::SetTrash($trash)}}
    {{Builder::SetFolder($folder)}}
    {{Builder::SetObject('group_inv')}}

    @include('crm::group_invs.search')
    @include('crm::group_invs.table')

@endsection
