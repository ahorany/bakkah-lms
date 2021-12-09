@extends('layouts.crm.form')
<link rel="stylesheet" href="{{CustomAsset('assets/css/jquery.datetimepicker.css')}}">

{!!Builder::SetPostType($post_type)!!}
{{Builder::SetFolder($folder)}}
{{Builder::SetNameSpace('training.')}}
{{Builder::SetPublishName('publish')}}

@section('col9')
    {!!Builder::CheckBox('active', 'active', null, ['col'=>'col-md-6'])!!}
@endsection

