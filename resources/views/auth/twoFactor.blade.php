@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-5">
            <div class="text-center my-3">
                <a href="{{route('education.index')}}" title="{{__('education.header_title')}}">
                    <img src="{{CustomAsset('images/logo.png')}}" alt="{{__('education.header_title')}}">
                </a>
            </div>
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        @if(session()->has('message'))
                            <p class="alert alert-info">
                                {{ session()->get('message') }}
                            </p>
                        @endif
                        <form method="POST" action="{{ route('twofactor.verify.store') }}">
                            {{ csrf_field() }}
                            <h1>Login Verification</h1>
                            <p class="text-muted">
                                You have received an email which contains login code.
                                If you haven't received it, press <a href="{{ route('twofactor.verify.resend') }}">here</a>.
                            </p>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <input name="two_factor_code" type="text" class="form-control{{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" required autofocus placeholder="Code..">
                                @if($errors->has('two_factor_code'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('two_factor_code') }}
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary px-4" style="background-color: #00BCB3;border-color: #00BCB3;">
                                        {{ __('admin.Verify') }}
                                    </button>
                                </div>
                                <div class="col-6 text-right">
                                    <a class="btn btn-danger px-4" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                        {{ __('admin.logout') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
@endsection
