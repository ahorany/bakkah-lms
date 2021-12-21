@extends('layouts.crm.create')

@section('useHead')
    <title>{{__('education.Create User')}} | {{ __('home.DC_title') }}</title>
@endsection

@section('create')
    {{Builder::SetNameSpace('training.')}}
    @include('training.'.$folder.'.form')
@endsection
