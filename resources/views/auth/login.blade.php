@extends('layouts.app')
<style>
    .container-fluid{
  min-height: 100vh;
  max-height: 100vh;
}

.bg-login{
  background: url({{CustomAsset('/images/login-bg.png')}});
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
}

h1{
  font-size: 50px !important;
}

.login-area{
  background-color: #fff;
  height: 100vh;
}

.input-group-text i {
  font-size: 13px !important;
  color: #777;
  text-align: center;
}

.input-group-text{
  width: 40px !important;
  background-color: #f4f4f4 !important;
}

input, .input-group-text{
  border: 1px solid #f0f0f0 !important;
}

button{
  font-weight: 600 !important;
}

.content, form {
  position: relative;
  top: 30vh;
  padding: 0 30px;
}

.content .brand{
  margin-bottom: 20px;
  color: #fff
}
.content span {
  font-weight: 600;
  color: #edd;
}

@media (min-width: 300px) and (max-width: 12000px) {
  .bg-login { display: block}
}

@media (min-width: 0px) and (max-width: 300px) {
  .bg-login { display: none}
}
</style>
@section('content')

<div class="container-fluid">
    <div class="row">
      <div class="col-md-8 bg-login">
      </div>
      <div class="col-md-4 login-area">
        <form method="POST" action="{{ route('login') }}">
            @csrf

          <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}" autofocus>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="form-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
        <div class="form-group row">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
        </div>
          <div class="form-group">
            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
          </div>
          <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>

          {{-- <p class="text-center my-3">or <a href="#">signup</a> ?</p> --}}
        </form>
      </div>
    </div>
  </div>
@endsection
