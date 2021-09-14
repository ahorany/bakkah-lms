@extends(ADMIN.'.general.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}

@section('col9')
@php
   $disabeld = true;
@endphp

@isset($eloquent)

@if(($eloquent->tracked_by == auth()->id() && $eloquent->updated_status) || ($eloquent->tracked_by == null && $eloquent->created_by !=  auth()->id()))
@php
   $disabeld = false;
@endphp
@endif
    {{-- @if( (  ($eloquent->tracked_by  == null ) || ( $eloquent->tracked_by == auth()->id() && $eloquent->updated_status) ) )
    @php
    $disabeld = true;
    @endphp

 @else
 @php
   $disabeld = false;
@endphp
    @endif --}}
@endisset

{{-- @dd($disabeld) --}}
    {!!Builder::Select('status', 'status', $status, $action == 'create' ? 445 : null , [
        'col'=>'col-md-6',
        'model_title'=>'trans_name',
        // 'disabled' => $action == 'create' ? true : false ,
        'disabled' => $disabeld ,
    ])!!}
    {!!Builder::Select('priority', 'priority', $priority, null, [
        'col'=>'col-md-6',
        'model_title'=>'trans_name',
        'disabled' => !$disabeld ,


    ])!!}
    {!!Builder::Select('issue', 'issue', $issue, null, [
        'col'=>'col-md-6',
        'model_title'=>'trans_name',
        'disabled' => !$disabeld ,
    ])!!}
    {!!Builder::Select('company', 'company', $company, null, [
        'col'=>'col-md-6',
        'model_title'=>'trans_name',
        'disabled' => !$disabeld ,
    ])!!}
{{--
        {!!Builder::Select('tracked_by', 'user', $users, null, [
            'col'=>'col-md-6',
            'model_title'=>'trans_name',
        ])!!} --}}


    {!!Builder::Input('title', 'title',null,array('col'=>'col-md-12','disabled'=>!$disabeld))!!}
    {!!Builder::Textarea('description', 'description',null,array('col'=>'col-md-12','disabled'=>!$disabeld))!!}

@endsection

@section('col3')
    <?php $title = __('admin.table'); ?>
    {!! Builder::File('ticket_file', 'ticket_file', null, ['col' => 'col-md-12']) !!}
    {!! Builder::PDFForm('ticket_file') !!}
@endsection


