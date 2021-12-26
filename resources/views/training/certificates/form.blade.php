@extends('layouts.crm.form')

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetNameSpace('training.')}}
{{Builder::SetPrefix('training.')}}
{{Builder::SetPublishName('publish')}}

@if(Session::has('message'))
    <div class="alert alert-danger">{{Session::get('message')}} </div>
@endif

@section('col9')

    {!! Builder::Input('en_title', 'certificate_name', null, ['col'=>'col-md-6']) !!}
    {!! Builder::Input('ar_title', 'certificate_name_ar', null, ['col'=>'col-md-6']) !!}
    {!!Builder::Select('direction', 'direction', $directions, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}
    {{-- {!! Builder::Input('margin', 'margin', null, ['col'=>'col-md-6']) !!} --}}
    {!!Builder::Select('font_type', 'font_type', $fonts, null, [
        'col'=>'col-md-6', 'model_title'=>'trans_name',
    ])!!}
    @if(isset($childs))

        <div class="col-md-12">
            <a href="{{route('training.certificates.add_new',['parent_id'=>$eloquent->id??null])}}" class="btn btn-sm btn-primary add_new" class="btn btn-sm btn-success">
                <i class="fa fa-plus"></i> {{__('admin.add_new')}} </a>
        </div>

    @endif
    <div class="row" style="width:100%">
        <div class="col-md-12 add_new_rich">
           @include('training.certificates.new_rich')
        </div>
    </div>
@endsection

@section('image')
	<?php $image_title = __('training.background'); ?>
	{!!Builder::File('background', 'background')!!}
@endsection

@section('col3')
<div class="row">
    <div class="col-md-12">
      <?php $title = trans('admin.users_manual'); ?>
    </div>
</div>
    @include('training.certificates.users_manual')
@endsection
