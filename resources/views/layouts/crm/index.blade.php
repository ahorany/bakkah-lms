@extends('layouts.crm.master')

@section('content')

    <style>
        table .btn-table {
            border: none !important;
            font-size: 12px;
            padding: 5px;
        }
        table td .title{
            padding: 5px;
        }
    </style>
	@include('Html.alert')

	@yield('table')
	@yield('js')

@endsection
