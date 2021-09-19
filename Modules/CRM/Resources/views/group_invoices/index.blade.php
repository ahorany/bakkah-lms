@extends(ADMIN.'.general.index')

@section('table')

    {{Builder::SetPostType($post_type)}}
    {{Builder::SetTrash($trash)}}
    {{Builder::SetFolder($folder)}}
    {{Builder::SetObject('group_invoice')}}

    @include('crm::group_invoices.search')
    @include('crm::group_invoices.table')

@endsection
