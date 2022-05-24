@extends('layouts.crm.create')

@section('useHead')
    <title>{{ __('education.Create Group') }} | {{ __('home.DC_title') }}</title>
@endsection

@section('create')
    {{Builder::SetNameSpace('training.')}}
    {{Builder::HasRouteParams(true)}}

    @include('training.'.$folder.'.form')
@endsection
