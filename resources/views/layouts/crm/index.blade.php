@extends('layouts.crm.master')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>

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
