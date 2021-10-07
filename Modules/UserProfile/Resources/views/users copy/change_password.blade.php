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

                    <form method="POST" action="{{ route('user.save_password') }}">
                        @csrf

                        <div class="text-center mb-4">
                            <h2>{{__('education.Change Password')}}</h2>
                            <label style="color: #767676;" for="save">Enter your registered email below to receive password reset code</label>
                        </div>

                        <div class="form-group position-relative">
                            <input type="password" name="old_password" value="{{ old('old_password') }}" placeholder="{{ __('education.Enter the old_password') }}" class="form-control @error('old_password') is-invalid @enderror">
                            @error('old_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <input type="password" name="new_password" value="{{ old('new_password') }}" placeholder="{{ __('education.Enter the new_password') }}" class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="{{ __('education.Enter the confirm_password') }}" class="form-control @error('password_confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button class="btn btn-primary btn-block mb-3">{{__('education.Change Password')}}</button>
                        <a href="#" style="color: #000" class="d-block text-center">Cancel</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
