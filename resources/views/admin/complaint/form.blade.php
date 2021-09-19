@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetEloquent($eloquent)}}
{{Builder::SetNameSpace('admin.')}}
{{Builder::SetObject('complaint')}}

<style>
    body {
        background: #000 !important;
    }
    tr.active {
        background-color: #cfffc2 !important;
    }
    .table td, .table th {
        font-size: 13px !important;
    }
</style>
@section('col9')
<div class="container-fluid">
    <div class="row">
        {!!Builder::Select('partner_id', 'partner_id', $partners, null, [
            'col'=>'col-md-6', 'model_title'=>'trans_name',
        ])!!}
        {!!Builder::Date('submission_date', 'submission_date',null,array('col'=>'col-md-6'))!!}
        {!!Builder::Select('department', 'department', $department, null, [
            'col'=>'col-md-6', 'model_title'=>'trans_name',
        ])!!}
        {!!Builder::Select('status', 'status', $status, null, [
            'col'=>'col-md-6', 'model_title'=>'trans_name',
        ])!!}
        {!!Builder::Textarea('description', 'description',null,array('col'=>'col-md-12'))!!}
    </div>
</div>
@endsection

@section('col3_block')

    @if ($eloquent)
        @include(ADMIN.'.complaint.notes', ['eloquent' => $eloquent])
    @endif
@endsection
