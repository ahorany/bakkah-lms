@extends(FRONT.'.education.layouts.master')
@section('useHead')
    <title>{{__('education.Reset Password')}} | {{ __('home.DC_title') }}</title>
@endsection
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5 form-login">
            <div class="card">
                {{-- <div class="card-header bg-primary text-white">{{ __('education.Reset Password') }}</div> --}}

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="text-center mb-4">
                            <h2>{{__('education.Verify Code')}}</h2>
                            <label style="color: #767676;" for="save">Enter your registered email below to receive password reset code</label>
                        </div>

                        <div class="form-group position-relative">
                            <input type="text" name="code" value="{{ old('code') }}" placeholder="{{ __('education.Ente the Code') }}" class="form-control @error('code') is-invalid @enderror">
                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button class="btn btn-primary btn-block mb-3">{{__('education.Verify')}}</button>
                        <a href="#" style="color: #000" class="d-block text-center">Resend Code</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
